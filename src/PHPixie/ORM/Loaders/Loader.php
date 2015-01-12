<?php

namespace PHPixie\ORM\Loaders;

abstract class Loader implements \IteratorAggregate
{
    protected $loaders;

    public function __construct($loaders)
    {
        $this->loaders = $loaders;
    }

    public function asArray($entitiesAsObjects = false)
    {
        $array = array();
        foreach ($this as $entity) {
            if ($entitiesAsObjects) {
                $entity = $entity->asObject(true);
            }
            $array[] = $entity;
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
