<?php

namespace PHPixie\ORM\Values;

class Preload
{
    protected $values;
    protected $properties = array();
    
    public function __construct($values)
    {
        $this->values = $values;
    }
    
    public function properties()
    {
        return array_values($this->properties);
    }
    
    public function addProperty($property)
    {
        $this->properties[$property->propertyName()] = $property;
        return $this;
    }
    
    public function getProperty($name)
    {
        if(!array_key_exists($name, $this->properties))
            return null;
        
        return $this->properties[$name];
    }
    
    public function addPath($path, $options = array())
    {
        $path = explode('.', $path);
        return $this->addExplodedPath($path, $options);
    }
    
    public function addExplodedPath($explodedPath, $options = array())
    {
        $propertyName = array_shift($explodedPath);
        $property = $this->getProperty($propertyName);
        if($property === null) {
            $property = $this->values->cascadingPreloadProperty($propertyName, $options);
            $this->addProperty($property);
        }
        
        if(!empty($explodedPath))
            $property->preload()->addExplodedPath($explodedPath, $options);
        
        return $this;
    }
    
    public function add($item, $options = array())
    {
        if(is_string($item))
            return $this->addPath($item, $options);
            
        if($item instanceof \PHPixie\ORM\Values\Preload\Property)
            return $this->addProperty($item);
            
        throw new \PHPixie\ORM\Exception\Query("Only string and Property instances can be used.");
    }
    
}