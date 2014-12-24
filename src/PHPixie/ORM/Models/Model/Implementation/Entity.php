<?php

namespace PHPixie\ORM\Models\Model\Implementation;

abstract class Entity implements \PHPixie\ORM\Models\Model\Entity
{
    protected $relationshipMap;
    protected $data;
    protected $config;
    protected $relationshipProperties;

    public function __construct($relationshipMap, $config, $data)
    {
        $this->relationshipMap = $relationshipMap;
        $this->config = $config;
        $this->data = $data;
        
        $propertyNames = $this->relationshipMap->entityPropertyNames($this->modelName());
        $this->relationshipProperties = array_fill_keys($propertyNames, null);
    }
    
    public function modelName()
    {
        return $this->config->model;
    }
    
    public function data()
    {
        return $this->data;
    }
    
    public function getField($name, $default = null)
    {
        return $this->data->get($name, $default);
    }
    
    public function setField($name, $value)
    {
        $this->data->set($name, $value);
        return $this;
    }
    
    public function getRelationshipProperty($name, $createMissing = true)
    {
        if (!array_key_exists($name, $this->relationshipProperties)) {
            throw new \PHPixie\ORM\Exception\Relationship("Relationship property '$name' is not defined for '{$this->modelName()}'");
        }
        
        return $this->relationshipProperty($name, $createMissing);
    }
    
    protected function relationshipProperty($name, $createMissing = true)
    {        
        $property = $this->relationshipProperties[$name];
        
        if($property === null && $createMissing) {
            $property = $this->relationshipMap->entityProperty($this, $name);
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
                    $data->$name = $property->asData(true);
                    
                }
            }
        }
        return $data;
    }
    
    public function __get($name)
    {
        if (array_key_exists($name, $this->relationshipProperties))
            return $this->relationshipProperty($name);
        
        return $this->getField($name);
    }
    
    public function __set($name, $value)
    {
        $this->setField($name, $value);
    }
    
}