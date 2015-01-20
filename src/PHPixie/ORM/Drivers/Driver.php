<?php

namespace PHPixie\ORM\Drivers;

abstract class Driver
{
    protected $configs;
    protected $conditions;
    protected $data;
    protected $database;
    protected $models;
    protected $maps;
    protected $mappers;
    protected $values;
    
    public function __construct($configs, $conditions, $data, $database, $models, $maps, $mappers, $values)
    {
        $this->configs    = $configs;
        $this->conditions = $conditions;
        $this->data       = $data;
        $this->database   = $database;
        $this->models     = $models;
        $this->maps       = $maps;
        $this->mappers    = $mappers;
        $this->values     = $values;
    }
    
    abstract public function config($modelName, $configSlice);
    abstract public function repository($config);
    abstract public function query($config);
    abstract public function entity($repository, $data, $isNew = true);
}
