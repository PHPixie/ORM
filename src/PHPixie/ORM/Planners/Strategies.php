<?php

namespace PHPixie\ORM\Planners;

class Strategies
{
    protected $planners;
    protected $steps;
    protected $strategies = array();
    
    public function __construct($planners, $steps)
    {
        $this->planners = $planners;
        $this->steps = $steps;
    }
    
    public function query($type)
    {
        return $this->instance('query', $type, 'buildQueryStrategy');
    }
    
    protected function buildQueryStrategy($type)
    {
        $class = '\PHPixie\ORM\Planners\Planner\Query\Strategy\\'.$name;
        return new $class;
    }
    
    public function in($type)
    {
        return $this->instance('in', $type, 'buildInStrategy');
    }
    
    protected function buildInStrategy($type)
    {
        $class = '\PHPixie\ORM\Planners\Planner\In\Strategy\\'.$name;
        return new $class;
    }
    
    public function pivot($type)
    {
        return $this->instance('pivot', $type, 'buildPivotStrategy');
    }
    
    protected function buildPivotStrategy($type)
    {
        $class = '\PHPixie\ORM\Planners\Planner\In\Strategy\\'.$name;
        return new $class($this->steps);
    }
    
    protected function instance($strategy, $type, $builderMethod)
    {
        if(!array_key_exists($strategy, $this->strategies))
            $this->strategies[$strategy] = array();
        
        if(!array_key_exists($type, $this->strategies[$strategy]))
            $this->strategies[$strategy][$type] = $this->$builderMethod($type);
        
        return $this->strategies[$strategy][$type];
    }
}