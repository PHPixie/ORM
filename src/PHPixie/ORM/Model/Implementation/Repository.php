<?php

namespace PHPixie\ORM\Repositories\Repository;

abstract class Implementation implements \PHPixie\ORM\Repositories\Repository
{
    protected $driver;
    protected $dataBuilder;
    protected $modelName;

    public function __construct($driver, $wrapper, $dataBuilder, $modelName)
    {
        $this->driver = $driver;
        $this->dataBuilder = $dataBuilder;
        $this->modelName = $modelName;
    }

    public function modelName()
    {
        return $this->modelName;
    }
    
    public function query()
    {
        $modelName = $this->modelName();
        $query = $this->driver->query($modelName);
        return $this->wrapper->query($modelName, $query);
    }
    
    protected function create()
    {
        return $this->entity();
    }
    
    protected function entity($isNew = true)
    {
        $modelName = $this->modelName();
        $entity = $this->driver->entity($modelName);
        return $this->wrapper->entity($modelName, $entity);
    }
    
    
    abstract public function save($model);
    abstract public function delete($model);
    abstract public function load($data);
    abstract public function model();
}
