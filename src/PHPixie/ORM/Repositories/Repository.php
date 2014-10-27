<?php

namespace PHPixie\ORM\Repositories;

abstract class Repository
{
    protected $dataBuilder;
    protected $modelName;
    
    public function __construct($dataBuilder, $modelName)
    {
        $this->dataBuilder = $dataBuilder;
        $this->modelName = $modelName;
    }

    public function modelName()
    {
        return $this->modelName;
    }

    abstract public function save($model);
    abstract public function delete($model);
    abstract public function load($data);
    abstract public function model();
}
