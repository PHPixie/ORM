<?php

namespace PHPixie\ORM\Plans\Plan;

abstract class Composite extends \PHPixie\ORM\Plans\Plan
{
    protected $subplans = array();

    protected function subplan($name, $createMissing = true)
    {
        if (!array_key_exists($name, $this->subplans))
            $this->subplans[$name] = $this->plans->plan();

        return $this->subplans[$name];
    }

    protected function subplanSteps($name)
    {
        if (($subplan = $this->subplan($name)) === null)
            return array();

        return $subplan->steps();
    }
}
