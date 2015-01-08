<?php

namespace PHPixie\ORM\Relationships\Relationship;

abstract class Implementation implements \PHPixie\ORM\Relationships\Relationship
{
    protected $configs;
    protected $models;
    protected $planners;
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $mappers;
    
    protected $handler;

    public function __construct($configs, $models, $planners, $plans, $steps, $loaders, $mappers)
    {
        $this->configs  = $configs;
        $this->models   = $models;
        $this->planners = $planners;
        $this->plans    = $plans;
        $this->steps    = $steps;
        $this->loaders  = $loaders;
        $this->mappers  = $mappers;
    }

    public function getSides($configSlice)
    {
        $config = $this->config($configSlice);
        $sides = array();
        foreach($this->sideTypes($config) as $type) {
            $sides[] = $this->side($type, $config);
        }
        return $sides;
    }

    public function handler()
    {
        if ($this->handler === null) {
            $this->handler = $this->buildHandler();
        }

        return $this->handler;
    }
    
    abstract public function entityProperty($side, $entity);
    
    abstract protected function buildHandler();
    abstract protected function config($config);
    abstract protected function side($type, $config);
    abstract protected function sideTypes($config);

}
