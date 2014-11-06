<?php

namespace PHPixie\ORM\Models\Model\Entity;

abstract class Implementation implements \PHPixie\ORM\Models\Model\Entity
{
    protected $relationshipMap;
    protected $relationshipProperties = array();

    public function __construct($relationshipMap, $config, $data)
    {
        $this->relationshipMap = $relationshipMap;
        $this->setData($data);
        $this->isNew = $isNew;
    }
    
    public function modelName()
    {
        return $this->config->modelName;
    }
    
    public function getField($name)
    {
        return $this->data->get($name);
    }
    
    public function setField($name, $value)
    {
        $this->data->set($name, $value);
    }
    
    public function asObject($recursive = false)
    {
        $data = $this->data->data();

        if($recursive) {
            foreach($this->relationshipProperties as $name => $property) {
                if($property->isLoaded()) {
                    $data->$name = $property->data(true);
                }
            }
        }
        return $data;
    }

    public function setRelationshipProperty($name, $property)
    {
        $this->relationshipProperties[$name] = $property;
    }

    public function __get($name)
    {
        $property = $this->getRelationshipProperty($name);
        if ($property !== null)
            return $property;
        
        return $this->getField($name);
    }
    
    public function __set($name, $value)
    {
        $this->data->set($name, $value);
    }
    
    public function data()
    {
        return $this->data;
    }

    public function getRelationshipProperty($name, $createMissing = true)
    {
        if (!array_key_exists($name, $this->relationshipProperties)) {
            if (!$createMissing)
                return null;

            $property = $this->relationshipMap->entityProperty($this, $name);

            if ($property === null)
                return null;

            $this->relationshipProperties[$name] = $property;
        }

        return $this->relationshipProperties[$name];
    }
    
}