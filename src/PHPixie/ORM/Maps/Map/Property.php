<?php

namespace PHPixie\ORM\Maps\Map;

abstract class Property extends \PHPixie\ORM\Maps\Map
{
    protected $relationships;
    
    public function __construct($relationships)
    {
        $this->relationships = $relationships;
    }
    
    public function getPropertyNames($modelName)
    {
        $this->ensureModel($modelName);
        return array_keys($this->sides[$modelName]);
    }
}