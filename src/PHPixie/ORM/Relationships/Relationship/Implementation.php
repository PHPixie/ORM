<?php

namespace PHPixie\ORM\Relationships\Relationship;

abstract class Implementation implements \PHPixie\ORM\Relationships\Relationship
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
            $this->handler = $this->buildHandler();
        }

        return $this->handler;
    }
    
    abstract public function entityProperty($side, $model);
    
    abstract protected function buildHandler();
    abstract protected function config($config);
    abstract protected function side($type, $config);
    abstract protected function sideTypes($config);
    abstract protected function buildHandler();

}
