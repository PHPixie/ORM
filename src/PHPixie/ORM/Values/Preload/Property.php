<?php

namespace PHPixie\ORM\Values\Preload;

class Property
{
    protected $propertyName;
     
    public function __construct($propertyName)
    {
        $this->propertyName = $propertyName;
    }
    
    public function propertyName()
    {
        return $this->propertyName;
    }
    
}