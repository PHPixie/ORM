<?php

namespace PHPixie\ORM\Loaders;

abstract class Loader implements \IteratorAggregate
{
    protected $loaders;

    public function __construct($loaders)
    {
        $this->loaders = $loaders;
    }

    public function asArray($modelsAsObjects = false)
    {
        $array = array();
        foreach ($this as $model) {
            if ($modelsAsObjects)
                $model = $model->asObject(true);
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
