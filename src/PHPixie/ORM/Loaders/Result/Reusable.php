<?php

namespace PHPixie\ORM\Loaders;

class Reusable extends \PHPixie\ORM\Loaders\Reusable
{
    protected $repository;
    protected $reusableResultStep;
    protected $models;
    
    public function __construct($orm, $repository, $reusableResultStep, $preloaders)
    {
        parent::__construct($orm, $repository, $preloaders);
        $this->reusableResultStep = $reusableResultStep;
    }
    
    public function offsetExists($offset)
    {
        $data = $this->reusableResultStep->offsetExists($offset);
    }
    
    public function getModelByOffset($offset)
    {
        if (!array_key_exists($offset, $this->models)) {
            $data = $this->reusableResultStep->getByOffset($offset);
            $this->models[$offset] = $this->loadModel($data);
        }
        
        return $this->models[$offset];
    }
}