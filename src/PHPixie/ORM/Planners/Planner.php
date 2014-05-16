<?php

namespace PHPixie\ORM\Planners;

abstract class Planner
{
    protected $planners;
    protected $steps;
    protected $repositoryRegistry;

    public function __construct($planners, $steps, $repositoryRegistry)
    {
        $this->planners = $planners;
        $this->steps = $steps;
        $this->repositoryRegistry = $repositoryRegistry;
    }
}
