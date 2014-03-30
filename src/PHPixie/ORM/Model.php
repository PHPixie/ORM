<?php

namespace PHPixie\ORM;

class Model
{
    protected $propertyBuilder;
    protected $properties;
    protected $isNew = true;
    
    public function __construct($propertyBuilder)
    {
        $this->propertyBuilder = $propertyBuilder;
    }

    public function asArray()
    {
        $data = $this->repository->modelData($this, $properties);
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
    
    public function setData($data)
    {
        $data->setModel($this);
        foreach($data->modelProperties() as $key => $value)
            $this->$key = $value;
    }
    
    public function data()
    {
        return $this->data;
    }
    
    public function dataProperties()
    {
        $dataProperties = get_object_vars($this);
        $classProperties = array_keys(get_class_vars(get_class($this)));
        foreach($classProperties as $property)
            unset($dataProperties[$property]);
            
        foreach($dataProperties as $key => $value)
            if($value instanceof \PHPixie\ORM\Relationship\Type\Property\Model)
                unset($dataProperties[$key]);
        
        return $dataProperties;
    }
    
    public function isNew()
    {
        return $this->isNew;
    }
    
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;
    }
}
