<?php

namespace PHPixie\ORM\Relationships;

abstract class Relationship
{
    public function getSides($config)
    {
        $config = $this->config($config);
        $sides = array();
        foreach($this->sideTypes($config) as $type)
            $sides[] = $this->side($type, $config);

        return $sides;
    }

    abstract public function config($config);
    abstract public function side($type, $config);
    abstract public function modelProperty($side, $model);
    abstract public function queryProperty($side, $model);

    abstract protected function sideTypes($config);

}
