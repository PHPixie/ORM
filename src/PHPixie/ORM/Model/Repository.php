<?php

namespace PHPixie\ORM\Model;

abstract class Repository
{
    protected $orm;
    protected $driver;
    protected $modelName;
    protected $connectionName;
    
    public function __construct($orm, $driver, $modelName, $config)
    {
        $this->orm = $orm;
        $this->driver = $driver;
        $this->modelName = $modelName;
    }
    
    public function modelName()
    {
        return $this->modelName;
    }
    
    abstract public function save($model);
    abstract public function delete($model);
    abstract public function load($data);
    abstract public function create();
}
