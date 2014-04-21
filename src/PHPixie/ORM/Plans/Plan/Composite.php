<?php

namespace PHPixie\ORM\Plans\Plan;

abstract class Composite extends \PHPixie\ORM\Plans\Plan
{
    protected $subplans = array();

    protected function subplan($name, $createMissing = true)
    {
        if (!array_key_exists($this->subplans[$name]))
            $this->subplans[$name] = $this->plans->plan();

        return $this->subplans[$name];
    }

    protected function executeSubplan($name)
    {
        if (array_key_exists($this->subplans[$name]))
            $this->subplans[$name]->execute();
    }

    protected function subplanSteps($name)
    {
        if (!array_key_exists($this->subplans[$name]))
            return array();

        return $this->subplans[$name]->steps();
    }
}
