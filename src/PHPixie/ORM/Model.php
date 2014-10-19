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
    
    public function asObject($recursive = false)
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
        
        return $this->getField($name);
    }
    
    public function getField($name)
    {
        return $this->data->get($name);
    }

    public function __set($name, $value)
    {
        $this->data->set($name, $value);
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
       
    }

    public function relationshipProperty($name, $createMissing = true)
    {
        return 5;
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