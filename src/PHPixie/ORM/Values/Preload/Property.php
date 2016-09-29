<?php

namespace PHPixie\ORM\Values\Preload;

class Property
{
    protected $options;
    protected $parameters;
     
    public function __construct($propertyName, $options)
    {
        $this->propertyName = $propertyName;
        $this->options = $options;
    }
    
    public function propertyName()
    {
        return $this->propertyName;
    }
    
    public function options()
    {
        return $this->options;
    }
}