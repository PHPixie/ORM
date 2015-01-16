<?php

namespace PHPixie\ORM\Planners;

class Planner
{
    protected $strategies = array();
    
    protected function strategy($type)
    {
        if(!array_key_exists($type, $this->strategies)) {
            $method = 'build'.ucfirst($type).'Strategy';
            $this->strategies[$type] = $this->$method();
        }
        
        return $this->strategies[$type];
    }
}