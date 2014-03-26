<?php

namespace PHPixie\ORM;

abstract class Driver
{
    protected $orm;

    public function __construct($orm)
    {
        $this->orm = $orm;
    }

    abstract public function repository($modelName, $modelConfig);

}
