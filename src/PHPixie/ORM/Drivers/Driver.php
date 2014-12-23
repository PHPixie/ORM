<?php

namespace PHPixie\ORM\Drivers;

abstract class Driver
{
    protected $ormBuilder;

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    abstract public function repository($database, $model, $config);
    abstract public function config($modelName);
    abstract public function query($values, $mapper, $relationshipMap, $container, $config);
    abstract public function entity($relationshipMap, $repository, $data, $isNew);

}
