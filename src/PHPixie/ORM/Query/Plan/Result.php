<?php

namespace \PHPixie\ORM\Query\Plan;

class Result extends \PHPixie\ORM\Query\Plan
{
    protected $orm;

    protected $requiredPlan;
    protected $resultPlan;
    protected $preloadPlan;

    public function __construct($orm)
    {
        $this->orm = $orm;
    }

    public function requiredPlan()
    {
        if ($this->requiredPlan === null)
            $this->requiredPlan = $this->orm->plan();

        return $this->requiredPlan;
    }

    public function resultPlan()
    {
        if ($this->resultPlan === null)
            $this->resultPlan = $this->orm->plan();

        return $this->resultPlan;
    }

    public function preloadPlan()
    {
        if ($this->preloadPlan === null)
            $this->preloadPlan = $this->orm->plan();

        return $this->preloadPlan;
    }

}
