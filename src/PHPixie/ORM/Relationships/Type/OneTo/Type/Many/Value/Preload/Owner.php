<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Value\Preload;

class Owner extends \PHPixie\ORM\Values\Preload\Property
{
    protected $owner;
    
    public function __construct($propertyName, $owner)
    {
        parent::__construct($propertyName);
        $this->owner = $owner;
    }
    
    public function owner()
    {
        return $this->owner;
    }
}