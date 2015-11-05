<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Planner
{
    protected $steps;
    
    public function __construct($steps)
    {
        $this->steps = $steps;
    }
    
    public function nodeResult($config, $nodes, $plan)
    {

    }
    
    public function initNode()
    {
        
    }
}
