<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Side\Config;

class Embedded
{
    protected $inflector;
    
    public function __construct($inflector)
    {
        $this->inflector = $inflector;
    }
    
    public function map($config, $defaultOwnerProperty)
    {
        return new Embedded\Map($this, $config, $defaultOwnerProperty);
    }
    
    public function config($propertyName, $config, $defaultOwnerProperty)
    {
        return new Embedded\Config($this, $this->inflector, $propertyName, $config, $defaultOwnerProperty);
    }
}