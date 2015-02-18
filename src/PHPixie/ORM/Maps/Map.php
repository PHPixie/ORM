<?php

namespace PHPixie\ORM\Maps;

abstract class Map
{
    protected $sides = array();
    
    public function add($side)
    {
        $modelName = $side->modelName();
        $propertyName = $side->propertyName();
        $this->ensureModel($modelName);

        if (array_key_exists($propertyName, $this->sides[$modelName])) {
            throw new \PHPixie\ORM\Exception\Relationship("Duplicate property '$propertyName' defined on '$modelName'");
        }
        
        $this->sides[$modelName][$propertyName] = $side;
    }
    
    public function get($modelName, $propertyName)
    {
        return $this->sides[$modelName][$propertyName];
    }
    
    public function getModelSides($modelName)
    {
        return $this->sides[$modelName];
    }
    
    protected function ensureModel($modelName)
    {
        if (!array_key_exists($modelName, $this->sides)) {
            $this->sides[$modelName] = array();
        }
    }
}