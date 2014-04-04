<?php

namespace PHPixie\ORM\Loaders\Loader\Preloadable\Result;

class Reusable extends \PHPixie\ORM\Loaders\Loader\Preloadable\Result
{
    protected $repository;
    protected $reusableResultStep;
    protected $models;
    
    public function __construct($loaders, $repository, $reusableResultStep)
    {
        parent::__construct($loaders, $repository);
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
    
    public function resultStep()
    {
        return $this->reusableResultStep;
    }
    
    public function repository()
    {
        return $this->repository;
    }
}