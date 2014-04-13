<?php

namespace PHPixie\ORM\Planners\Planner\Pivot;

abstract class Strategy
{

    protected $steps;
    
    public function __construct($steps)
    {
        $this->steps = $steps;
    }
    
    abstract public function link($pivot, $firstSide, $secondSide, $plan);
    abstract public function unlink($pivot, $firstSide, $secondSide, $plan);
}