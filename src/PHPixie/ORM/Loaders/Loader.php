<?php

namespace PHPixie\ORM\Loaders;

abstract class Loader implements \IteratorAggregate
{
    protected $loaders;
    protected $preloaders = array();
    
    public function __construct($loaders)
    {
        $this->loaders = $loaders;
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
    
    public function getIterator() {
        return $this->loaders->iterator($this);
    }
    
    public abstract function offsetExists($offset);
    protected abstract function getByOffset($offset);
}