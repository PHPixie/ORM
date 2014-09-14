<?php

namespace PHPixie\ORM\Plans\Plan\Composite;

class Loader extends \PHPixie\ORM\Plans\Plan\Composite
{
    protected $updateStep;

    public function updateStep()
    {
        return $this->updateStep;
    }

    public function setUpdateStep($updateStep)
    {
        $this->updateStep = $updateStep;
    }

    public function requiredPlan()
    {
        return $this->subplan('required');
    }

    public function steps()
    {
        $steps = $this->subplanSteps('required');
        $steps[]= $this->updateStep;

        return $steps;
    }

}
