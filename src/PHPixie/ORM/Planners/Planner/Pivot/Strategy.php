<?php

namespace PHPixie\ORM\Planners\Planner\Pivot;

abstract class Strategy
{
    protected $planners;
    protected $steps;

    public function __construct($planners, $steps)
    {
        $this->planners = $planners;
        $this->steps    = $steps;
    }

    protected function idQuery($side, $plan)
    {
        $query = $side->repository()->query();
        $query->in($side->items());
        $queryPlan = $query->planFind();
        
        $plan->appendPlan($queryPlan->requiredPlan());
        $query = $queryPlan->queryStep()->query();
        $idField = $side->repository()->config()->idField;
        $query->fields(array($idField));
        return $query;
    }
    
    abstract public function link($pivot, $firstSide, $secondSide, $plan);
}
