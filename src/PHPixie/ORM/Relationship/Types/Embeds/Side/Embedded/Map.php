<?php

namespace PHPixie\ORM\Relationships\Types\Embeds\Side\Config\Embedded;

class Map {
    
    protected $map = array();
    
    public function __construct($embedded, $config, $defaultOwnerProperty)
    {
        $properties = array_keys($config->get(null, array()));
        
        foreach($properties as $property) {
            $this->map[$property] = $embedded->config($property, $config->slice($property), $defaultOwnerProperty);
        }
    }
}