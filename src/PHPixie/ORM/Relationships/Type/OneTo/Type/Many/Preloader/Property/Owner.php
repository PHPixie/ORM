<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Preloader\Property;

class Owner extends \PHPixie\ORM\Relationships\Relationship\Preloader
{
    protected $owner;
    
    public function __construct($loader, $owner)
    {
        parent::__construct($loader);
        $this->owner = $owner;
    }
    
    public function valueFor($model)
    {
        return $this->owner;
    }
}