<?php

namespace PHPixie\ORM\Values\Preload;

class Property
{
    protected $propertyName;
    protected $options;
     
    public function __construct($propertyName, $options = array())
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
