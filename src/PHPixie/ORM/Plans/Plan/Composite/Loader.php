<?php

namespace PHPixie\ORM\Plans\Plan\Composite;

class Loader extends \PHPixie\ORM\Plans\Plan\Composite
{
    protected $resultStep;

    public function resultStep()
    {
        return $this->resultStep;
    }

    public function setResultStep($resultStep)
    {
        $this->resultStep = $resultStep;
    }

    public function requiredPlan()
    {
        return $this->subplan('required');
    }

    public function preloadPlan()
    {
        return $this->subplan('preload');
    }

    public function execute()
    {
        $this->executeSubplan('required');
        $this->resultStep->execute();
        $this->executeSubplan('preload');
    }

    public function steps()
    {
        $steps = $this->subplanSteps('required');
        $steps[]= $this->resultStep;

        return array_merge($steps, $this->subplanSteps('preload'));
    }
}
