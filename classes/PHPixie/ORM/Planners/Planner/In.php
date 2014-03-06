<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class In extends \PHPixie\ORM\Query\Plan\Planner
{
    public function collection($logic, $negated, $query, $queryField, $collection, $collectionField, $plan)
    {
        $query->startWhereGroup($logic, $negated);
        $collectionConnection = $this
                            ->repositoryRegistry($collection->modelName())
                            ->connection();
        $method = $this->selectStrategy($dbQuery->connection(), $collectionConnection);
        $ids = $collection->getField($collectionField);
        if (!empty($ids))
            $query->orWhere($field, 'in', $ids);

        foreach ($collection->addedQueries() as $query) {
            $subplan = $query->planFind();
            $plan->appendPlan($subplan->requiredPlan());
            $subquery = $subplan->resultStep()->query();
            $strategy->$method('or', false, $query, $queryField, $subquery, $collectionField, $plan);
        }

        $query->endWhereGroup();
    }

    public function subquery($logic, $negated, $query, $queryField, $subquery, $subqueryField, $plan)
    {
        $method = $this->method($dbQuery->connection(), $subqery->connection());
        $strategy->$method($logic, $negate, $query, $queryField, $subquery, $subqueryField, $plan);
    }

    protected function subqueryMethod($logic, $negate, $query, $queryField, $subquery, $subqueryField, $plan)
    {
        $subquery->fields(array($subqueryField));
        $query->getWhereBuilder()->addOperatorCondition($logic, $negate, $queryField, 'in', $subquery);
    }

    protected function multiqueryMethod($logic, $negate, $query, $queryField, $subquery, $subqueryField, $plan)
    {
        $subquery->fields(array($subqueryField));
        $resultStep = $this->steps->result($subquery);
        $plan->push($resultStep);
        $placeholder = $query->getWhereBuilder()->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $subqueryField);
        $plan->push($inStep);
    }

    protected function method($queryConnection, $subqueryConnection)
    {
        if ($queryConnection instanceof PHPixie\DB\Driver\PDO\Connection && $queryConnection === $subqueryConnection)
            return 'subquery_method';
        return 'multiquery_method';
    }

}
