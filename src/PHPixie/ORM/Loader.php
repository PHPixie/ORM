<?php

namespace PHPixie\ORM;

abstract class Loader implements \IteratorAggregate
{
    protected $orm;
    protected $preloaders;
    
    public function __construct($orm, $preloaders)
    {
        $this->orm = $orm;
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
    
    protected function processPreloaders($model)
    {
        foreach($this->preloaders as $property => $preloader)
            $model->$property->setValue($preloader->loadFor($model));
    }
    
    public abstract function getByOffset($offset){
        $model = $this->getModelByOffset($offset);
        $this->processPreloaders($model);
        return $model;
    }
    
    public function getIterator() {
        return $this->orm->loaderIterator($this);
    }
    
    public abstract function offsetExists($offset);
    protected abstract function getModelByOffset($offset);
}