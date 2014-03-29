<?php

namespace PHPixie\ORM\Loaders;

abstract class Loader implements \IteratorAggregate
{
    protected $loaders;
    protected $preloaders;
    
    public function __construct($loaders, $preloaders)
    {
        $this->loaders = $loaders;
        $this->preloaders = $preloaders;
    }
    
    public function asArray($modelsAsArrays = false)
    {
        $array = array();
        foreach($this as $model) {
            if ($modelsAsArrays)
                $model = $model->asArray();
            $array[] = $model;
        }
        return $array;
    }
    
    public function getByOffset($offset){
        $model = $this->getModelByOffset($offset);
        foreach($this->preloaders as $property => $preloader)
            $model->$property->setValue($preloader->loadFor($model));
        return $model;
    }
    
    public function getIterator() {
        return $this->loaders->iterator($this);
    }
    
    public abstract function offsetExists($offset);
    protected abstract function getModelByOffset($offset);
}