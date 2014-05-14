<?php

namespace PHPixie\ORM\Planners\Planner\In;

abstract class Strategy
{

    protected $steps;

    public function __construct($steps)
    {
        $this->steps = $steps;
    }

    abstract public function field($subquery, $subqueryField);
}
