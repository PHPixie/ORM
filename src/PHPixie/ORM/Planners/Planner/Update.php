<?php

namespace \PHPixie\ORM\Planners\Planner;

class Update extends \PHPixie\ORM\Planners\Planner
{
    protected $modifiers;
    
    public function modifiers()
    {
        if(!isset($this->modifiers))
            $this->modifiers = $this->buildModifiers();
        
        return $this->modifiers;
    }
    
    public function buildModifiers()
    {
        return new Update\Modifiers($this->planners, $this->steps);
    }
    
    public function plan($query, $modifiers, $plan)
    {
        $updateStep = $this->steps->update($query);
        
        foreach($modifiers as $modifier) {
            $modifier->plan($updateStep, $plan);
        }
        
        $plan->add($updateStep);
    }
}
