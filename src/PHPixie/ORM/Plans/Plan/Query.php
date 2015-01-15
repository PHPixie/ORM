<?php

namespace PHPixie\ORM\Plans\Plan;

class Query extends \PHPixie\ORM\Plans\Plan
{
    protected $plans;
    protected $queryStep;
    
    protected $requiredPlan;
    
    public function __construct($plans, $queryStep)
    {
        parent::__construct($plans);
        $this->queryStep = $queryStep;
    }
    
    public function requiredPlan()
    {
        if($this->requiredPlan === null) {
            $this->requiredPlan = $this->plans->steps();
        }
        
        return $this->requiredPlan;
    }

    public function steps()
    {
        if($this->requiredPlan !== null) {
            $steps = $this->requiredPlan->steps();
        }else{
            $steps = array();
        }
        
        $steps[]= $this->queryStep();
        return $steps;
    }
    
    public function queryStep()
    {
        return $this->queryStep;
    }
}
