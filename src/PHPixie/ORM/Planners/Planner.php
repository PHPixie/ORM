<?php

namespace PHPixie\ORM\Planners;

abstract class Planner
{
    protected $strategies;
    
    public function __construct($strategies)
    {
        $this->strategies = $strategies;
    }
}
