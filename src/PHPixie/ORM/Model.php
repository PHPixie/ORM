<?php

namespace PHPixie\ORM;

class Model
{
    protected $relationshipMap;
    protected $properties = array();

    public function __construct($relationshipMap, $data, $isNew = true)
    {
        $this->relationshipMap = $relationshipMap;
        $this->setData($data);
        $this->isNew = $isNew;
    }
    
    public function modelName()
    {
    
    }
    
    public function asObject($recursive = true)
    {
        $data = $this->repository->modelAsObject($this);

        if($recursive && !$this->isDeleted())
            foreach($this->properties as $name => $property)
                if($property->isLoaded())
                    $data->$name = $property->data();

        return $data;
    }

    public function save()
    {
        $this->repository->save($this);

        return $this;
    }

    public function setRelationshipProperty($relationship, $property)
    {
        $this->relationship = $property;
    }
    
    public function __get($name)
    {

        $property = $this->relationshipProperty($name);
        if ($property !== null)
            return $property;

        throw new \PHPixie\Exception\Model("Property '$name' doesn't exist");
    }

    protected function setData($data)
    {
        $data->setModel($this);
        foreach($data->properties() as $key => $value)
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
            if($value instanceof \PHPixie\ORM\Relationships\Relationship\Property\Model)
                unset($dataProperties[$key]);

        return $dataProperties;
    }

    public function relationshipProperty($name, $createMissing = true)
    {
        if (!array_key_exists($name, $this->properties)) {
            if (!$createMissing)
                return null;

            $property = $this->relationshipMap->modelProperty($this, $name);

            if ($property === null)
                return null;

            $this->properties[$name] = $property;
            $this->$name = $property;
        }

        return $this->properties[$name];
    }
    
}