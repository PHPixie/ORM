<?php

namespace PHPixie\ORM\Models\Model\Implementation;

abstract class Entity implements \PHPixie\ORM\Models\Model\Entity
{
    protected $relationshipMap;
    protected $data;
    protected $config;
    protected $relationshipProperties = array();

    public function __construct($relationshipMap, $config, $data)
    {
        $this->relationshipMap = $relationshipMap;
        $this->config = $config;
        $this->data = $data;
    }
    
    public function modelName()
    {
        return $this->config->modelName;
    }
    
    public function data()
    {
        return $this->data;
    }
    
    public function getField($name)
    {
        return $this->data->get($name);
    }
    
    public function setField($name, $value)
    {
        $this->data->set($name, $value);
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

    
    public function asObject($recursive = false)
    {
        $data = $this->data->data();

        if($recursive) {
            foreach($this->relationshipProperties as $name => $property) {
                if($property instanceof \PHPixie\ORM\Relationships\Relationship\Property\Entity\Data && $property->isLoaded()) {
                    $data->$name = $property->data(true);
                }
            }
        }
        return $data;
    }
    
}