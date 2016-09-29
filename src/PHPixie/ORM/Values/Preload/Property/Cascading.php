<?php

namespace PHPixie\ORM\Values\Preload\Property;

class Cascading extends \PHPixie\ORM\Values\Preload\Property
{
    protected $preload;
    
    public function __construct($propertyName, $preload, $options = array())
    {
        parent::__construct($propertyName, $options);
        $this->preload = $preload;
    }
    
    public function preload()
    {
        return $this->preload;
    }
}