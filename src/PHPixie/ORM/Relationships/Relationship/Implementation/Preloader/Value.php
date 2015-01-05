<?php

namespace PHPixie\ORM\Relationships\Relationship\Implementation;

abstract class Value extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader
{
    protected $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function loadProperty($property)
    {
        $property->setValue($this->value);
    }
}
