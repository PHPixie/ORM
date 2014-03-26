<?php

namespace PHPixie\ORM;

class  Model
{
    protected $propertyBuilder;

    public function __construct($propertyBuilder)
    {
        $this->propertyBuilder = $propertyBuilder;
    }

    public function asArray()
    {
        return $this->repository->modelData($this);
    }

    public function save()
    {
        $this->repository->save($this);

        return $this;
    }

    public function __get($name)
    {
        $property = $this->propertyBuilder->modelProperty($this, $name);
        if ($property !== null)
            return $this->$name = $property;
    }
}
