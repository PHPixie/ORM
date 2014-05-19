<?php

namespace PHPixie\ORM\Planners\Planner;

abstract class Strategy extends \PHPixie\ORM\Planners\Planner
{
    protected $strategies;
    
    public function __construct($strategies)
    {
        $this->strategies = $strategies;
    }
}