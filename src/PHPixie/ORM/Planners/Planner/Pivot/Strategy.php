<?php

namespace PHPixie\ORM\Planners\Planner\Pivot;

abstract class Strategy
{
    protected $planners;
    protected $steps;

    public function __construct($planners, $steps)
    {
        $this->planners = $planners;
        $this->steps    = $steps;
    }

    abstract public function link($pivot, $firstSide, $secondSide, $plan);
}
