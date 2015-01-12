<?php

namespace PHPixie\ORM\Plans\Plan\Query;

class Loader extends \PHPixie\ORM\Plans\Plan\Query
{
    protected $loader;
    protected $preloadPlan;
    
    public function __construct($plans, $resultStep, $loader)
    {
        $this->loader = $loader;
        parent::__construct($plans, $resultStep);
    }

    public function preloadPlan()
    {
        if($this->preloadPlan === null) {
            $this->preloadPlan = $this->plans->steps();
        }
        
        return $this->preloadPlan;
    }

    public function steps()
    {
        $steps = parent::steps();
        if($this->preloadPlan !== null) {
            foreach($this->preloadPlan->steps() as $step) {
                $steps[] = $step;
            }
        }
        
        return $steps;
    }
    
    public function execute()
    {
        parent::execute();
        return $this->loader;
    }

}
