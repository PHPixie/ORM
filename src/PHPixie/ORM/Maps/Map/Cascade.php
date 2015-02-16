<?php

namespace PHPixie\ORM\Maps\Map;

abstract class Cascade extends \PHPixie\ORM\Maps\Map
{
    public function hasModelSides($modelName)
    {
        if(!array_key_exists($modelName, $this->sides)) {
            return false;
        }
        
        return count($this->sides[$modelName]) > 0;
    }
}