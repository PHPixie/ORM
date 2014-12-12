<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property;

class Owner extends \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader
{
    protected $owner;
    
    public function __construct($owner)
    {
        $this->owner = $owner;
    }
    
    public function loadProperty($property)
    {
        $property->setValue($this->owner);
    }
}
