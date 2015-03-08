<?php

namespace PHPixie;

class ORM
{
    protected $builder;
    
    public function __construct($database, $configSlice, $wrappers = null)
    {
        $this->builder = $this->buildBuilder($database, $configSlice, $wrappers);
    }
    
    public function repository($modelName)
    {
        return $this->databaseModel()->repository($modelName);
    }
    
    public function query($modelName)
    {
        return $this->databaseModel()->query($modelName);
    }
    
    public function createEntity($modelName, $data = null)
    {
        return $this->repository($modelName)->create($data);
    }
    
    public function repositories()
    {
        return $this->builder->repositories();
    }
    
    public function builder()
    {
        return $this->builder;
    }
    
    protected function databaseModel()
    {
        return $this->builder->models()->database();
    }
    
    protected function buildBuilder($database, $configSlice, $wrappers)
    {
        return new ORM\Builder($database, $configSlice, $wrappers);
    }
}
