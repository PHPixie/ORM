<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class In extends \PHPixie\ORM\Query\Plan\Planner
{
    public function collection($query, $queryField, $collection, $collectionField, $plan, $logic = 'and', $negate = false)
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
            $strategy->$method($query, $queryField, $subquery, $collectionField, $plan, 'or', false);
        }

        $query->endWhereGroup();
    }

    public function subquery($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $method = $this->method($dbQuery->connection(), $subqery->connection());
        $strategy->$method($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate);
    }
    
    public function loader($query, $queryField, $loader, $loaderField, $plan, $logic = 'and', $negate = false)
    {
        $this->result($query, $queryField, $loader->resultStep(), $loaderField, $plan, $logic, $negate);
    }
    
    public function result($query, $queryField, $resultStep, $resultField, $plan, $logic = 'and', $negate = false)
    {
        $placeholder = $query->getBuilder()->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $resultField);
        $plan->push($inStep);
    }
    
    protected function subqueryMethod($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate)
    {
        $subquery->fields(array($subqueryField));
        $query->getWhereBuilder()->addOperatorCondition($logic, $negate, $queryField, 'in', $subquery);
    }

    protected function multiqueryMethod($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate)
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
