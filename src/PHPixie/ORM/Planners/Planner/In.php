<?php

namespace \PHPixie\ORM\Planners\Planner;

class In extends \PHPixie\ORM\Planners\Planner\Strategy
{
    public function collection($query, $queryField, $collection, $collectionField, $plan, $logic = 'and', $negate = false)
    {
        $query->startWhereGroup($logic, $negated);
        $ids = $collection->modelField($collectionField);
        if (!empty($ids))
            $query->orWhere($queryField, 'in', $ids);

        $collectionQueries = $collection->addedQueries();
        if (!empty($collectionQueries)) { 
            $collectionConnection = $this
                            ->repositoryRegistry($collection->modelName())
                            ->connection();
            $strategy = $this->selectStrategy($query->connection(), $collectionConnection);
            foreach ($collection->addedQueries() as $collectionQuery) {
                $subplan = $collectionQuery->planFind();
                $plan->appendPlan($subplan->requiredPlan());
                $subquery = $subplan->resultStep()->query();
                $strategy->in($query, $queryField, $subquery, $collectionField, $plan, 'or', false);
            }
        }

        $query->endWhereGroup();
    }

    public function result($query, $queryField, $resultStep, $resultField, $logic = 'and', $negate = false)
    {
        $placeholder = $query->getWhereBuilder()->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $resultField);
        $plan->push($inStep);
    }
    
    public function loader($query, $queryField, $loader, $loaderField, $plan, $logic = 'and', $negate = false)
    {
        $this->result($query, $queryField, $loader->resultStep(), $loaderField, $plan, $logic, $negate);
    }
    
    public function subquery($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $strategy = $this->selectStrategy($dbQuery->connection(), $subqery->connection());
        $strategy->in($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate);
    }
    
    protected function selectStrategy($queryConnection, $subqueryConnection)
    {
        if ($queryConnection instanceof PHPixie\DB\Driver\PDO\Connection && $queryConnection === $subqueryConnection)
            return $this->strategy('subquery');
        return $this->strategy('multiquery');
    }
    
    protected function buildStrategy($name)
    {
        $class = '\PHPixie\ORM\Planners\Planner\In\Strategy\\'.$name;
        return new $class($this->steps);
    }
}
