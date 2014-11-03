<?php

namespace PHPixie\ORM\Models\Implementation;

abstract class Repository implements \PHPixie\ORM\Models\Repository
{
    protected $models;
    protected $modelName;

    public function __construct($models, $modelName)
    {
        $this->models = $models;
        $this->modelName = $modelName;
    }

    public function modelName()
    {
        return $this->modelName;
    }
    
    public function query()
    {
        $modelName = $this->modelName();
        return $this->models->query($modelName);
    }
    
    public function create()
    {
        return $this->entity();
    }
    
    public function load($data)
    {
        return $this->entity(false, $data);
    }
    
    protected function entity($isNew = true, $data = null)
    {
        $modelName = $this->modelName();
        $data = $this->buildData($data);
        return $this->models->entity($modelName, $isNew, $data);
    }
    
    abstract public function save($model);
    abstract public function delete($model);
    abstract protected function buildData($data);
}
