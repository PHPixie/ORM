<?php

namespace PHPixie\ORM\Drivers;

abstract class Driver
{
    protected $ormBuilder;

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    abstract public function repository($modelName);
    abstract public function config($modelName);
    abstract public function query($modelName);

}
