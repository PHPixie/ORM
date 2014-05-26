<?php

namespace PHPixie\ORM\Loaders;

abstract class Loader implements \IteratorAggregate
{
    protected $loaders;

    public function __construct($loaders)
    {
        $this->loaders = $loaders;
    }

    public function asArray($modelsAsArrays = false)
    {
        $array = array();
        foreach ($this as $model) {
            if ($modelsAsArrays)
                $model = $model->asArray();
            $array[] = $model;
        }

        return $array;
    }

    public function getIterator()
    {
        return $this->loaders->iterator($this);
    }

    abstract public function offsetExists($offset);
    abstract public function getByOffset($offset);
}
