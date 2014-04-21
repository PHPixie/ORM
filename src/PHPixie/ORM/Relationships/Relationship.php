<?php

namespace PHPixie\ORM\Relationships;

abstract class Relationship
{
    protected $ormBuilder;
    protected $handler;

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    public function getSides($config)
    {
        $config = $this->config($config);
        $sides = array();
        foreach($this->sideTypes($config) as $type)
            $sides[] = $this->side($type, $config);

        return $sides;
    }

    public function handler()
    {
        if ($this->handler === null) {
            $repositoryRegistry = $this->ormBuilder->repositoryRegistry();
            $planners = $this->ormBuilder->planners();
            $steps = $this->ormBuilder->steps();
            $loaders = $this->ormBuilder->loaders();
            $groupMapper = $this->ormBuilder->groupMapper();
            $cascadeMapper = $this->ormBuilder->cascadeMapper();
            $this->handler = $this->buildHandler($repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper);
        }

        return $this->handler;
    }

    abstract public function config($config);
    abstract public function side($type, $config);
    abstract public function modelProperty($side, $model);
    abstract public function queryProperty($side, $model);
    abstract protected function sideTypes($config);
    abstract protected function buildHandler($repositoryRegistry, $planners, $steps, $loaders, $groupMapper, $cascadeMapper);

}
