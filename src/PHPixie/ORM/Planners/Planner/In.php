<?php

namespace PHPixie\ORM\Planners\Planner;

class In extends \PHPixie\ORM\Planners\Planner
{
	
    protected $strategies;
    protected $steps;
    
    public function __construct($strategies, $steps)
    {
        $this->strategies = $strategies;
        $this->steps = $steps;
    }
    
    public function collection($query, $queryField, $collection, $collectionField, $plan, $logic = 'and', $negate = false)
    {
        $query->startWhereGroup($logic, $negate);
        $ids = $collection->modelField($collectionField);
        if (!empty($ids))
            $query->where($queryField, 'in', $ids);

        $collectionQueries = $collection->queries();
        if (!empty($collectionQueries)) {
            $strategy = $this->selectStrategy($query->connection(), $collection->connection());
            foreach ($collectionQueries as $collectionQuery) {
                $subplan = $collectionQuery->planFind();
                $plan->appendPlan($subplan->requiredPlan());
                $subquery = $subplan->resultStep()->query();
                $strategy->in($query, $queryField, $subquery, $collectionField, $plan, 'or', false);
            }
        }

        $query->endWhereGroup();
    }

    public function result($query, $queryField, $resultStep, $resultField, $plan, $logic = 'and', $negate = false)
    {
        $placeholder = $query->getWhereBuilder()->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $resultField);
        $plan->add($inStep);
    }

    public function subquery($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $strategy = $this->selectStrategy($dbQuery->connection(), $subqery->connection());
        $strategy->in($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate);
    }

    protected function selectStrategy($queryConnection, $subqueryConnection)
    {
        if ($queryConnection instanceof \PHPixie\Database\Driver\PDO\Connection && $queryConnection === $subqueryConnection)
            return $this->strategies->in('subquery');
        return $this->strategies->in('multiquery');
    }
}
