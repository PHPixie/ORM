<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Side\Config\Embedded;

class Map {
    
    protected $map = array();
    
    public function __construct($embedded, $config)
    {
        $properties = array_keys($config->get(null, array()));
        
        foreach($properties as $property) {
            $this->embedded[$property] = $embedded->config($config->slice($property), $property);
        }
    }
}