<?php

namespace PHPixie\ORM\Plans\Plan\Query;

class Loader extends \PHPixie\ORM\Plans\Plan\Query
{
    protected $preloadPlan;
    protected $loader;
    
    public function __construct($transaction, $requiredPlan, $preloadPlan, $loader)
    {
        $this->loader = $loader;
        $queryStep = $loader->reusableResultStep();
        parent::__construct($transaction, $requiredPlan, $queryStep);
        
        $this->preloadPlan = $preloadPlan;
    }

    public function preloadPlan()
    {
        return $this->preloadPlan;
    }

    public function steps()
    {
        $steps = parent::steps();
        foreach($this->preloadPlan->steps() as $step) {
            $steps[] = $step;
        }
        
        return $steps;
    }
    
    public function execute()
    {
        parent::execute();
        return $this->loader;
    }

}
