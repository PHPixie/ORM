<?php

namespace PHPixie\ORM\Planners\Planner;

class In extends \PHPixie\ORM\Planners\Planner
{
    protected $steps;
    
    public function __construct($steps)
    {
        $this->steps = $steps;
    }
    
    public function databaseModelQuery($query, $queryField, $modelQuery, $queryField, $plan, $logic = 'and', $negate = false)
    {
        $queryPlan = $modelQuery->planFind();
        $plan->appendPlan($queryPlan->preloadPlan());
        $resultStep = $queryPlan->queryStep();
        $plan->add($resultStep);
        
        $this->result($query, $queryField, $resultStep, $queryField, $plan, $logic, $negate);
        
    }
    
    public function result($query, $queryField, $resultStep, $resultField, $plan, $logic = 'and', $negate = false)
    {
        $placeholder = $query->getWhereBuilder()->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $resultField);
        $plan->add($inStep);
    }

    public function subquery($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $strategy = $this->selectStrategy($query->connection(), $subquery->connection());
        $strategy->in($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate);
    }
    
    protected function selectStrategy($queryConnection, $subqueryConnection)
    {
        if (!($queryConnection instanceof \PHPixie\Database\Type\SQL\Connection)) {
            return $this->strategies->in('multiquery');
        }
        
        if ($queryConnection !== $subqueryConnection) {
            return $this->strategies->in('multiquery');
        }
        
        return $this->strategies->in('subquery');
    }
    
    protected function buildSubqueryStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\In\Strategy\Subquery(
            $this->steps
        );
    }
    
    protected function buildMultiqueryStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\In\Strategy\Multiquery(
            $this->steps
        );
    }
}
