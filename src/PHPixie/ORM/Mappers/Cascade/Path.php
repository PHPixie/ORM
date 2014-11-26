<?php

namespace PHPixie\ORM\Mappers\Cascade;

class Path
{
    protected $mappers;
    protected $sides = array();
    protected $models = array();
    
    public function __construct($mappers)
    {
        $this->mappers = $mappers;
    }
    
    public function addSide($side)
    {
        $this->sides[]= $side;
        $this->models[$side->modelName()] = true;
    }
    
    public function containsModel($modelName)
    {
        return array_key_exists($modelName, $this->models);
    }
    
    public function sides()
    {
        return $this->sides;
    }
    
    public function copy()
    {
        $path = $this->mappers->cascadePath($this->sides);
        foreach($this->sides as $side) {
            $path->addSide($side);
        }
        return $path;
    }
}