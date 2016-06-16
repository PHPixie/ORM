<?php

namespace PHPixie\ORM\Loaders;

abstract class Loader implements \IteratorAggregate
{
    protected $loaders;

    public function __construct($loaders)
    {
        $this->loaders = $loaders;
    }

    public function asArray($entitiesAsObjects = false, $keyField = null)
    {
        $array = array();
        $withKey = $keyField === null;

        foreach ($this as $entity) {
            $value = $entity;
            if ($entitiesAsObjects) {
                $value = $entity->asObject(true);
            }
            
            if($withKey) {
                $array[] = $value;
            }else{
                $field = $entity->getField($keyField);
                $array[$field] = $value;
            }
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
