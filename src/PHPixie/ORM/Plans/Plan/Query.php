<?php

namespace PHPixie\ORM\Plans\Plan;

class Query extends \PHPixie\ORM\Plans\Plan
{
    protected $requiredPlan;
    protected $queryStep;
    
    public function __construct($transaction, $requiredPlan, $queryStep)
    {
        parent::__construct($transaction);
        $this->requiredPlan = $requiredPlan;
        $this->queryStep = $queryStep;
    }
    
    public function requiredPlan()
    {
        return $this->requiredPlan;
    }

    public function steps()
    {
        $steps = $this->requiredPlan->steps();
        $steps[]= $this->queryStep();

        return $steps;
    }
    
    protected function queryStep()
    {
        return $this->queryStep;
    }
}
