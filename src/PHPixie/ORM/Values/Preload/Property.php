<?php

namespace PHPixie\ORM\Values\Preload;

class Property
{
    protected $propertyName;
    protected $preload;
    
    public function __construct($propertyName, $preload)
    {
        $this->propertyName = $propertyName;
        $this->preload = $preload;
    }
    
    public function propertyName()
    {
        return $this->propertyName;
    }
    
    public function preload()
    {
        return $this->preload;
    }
}