<?php

namespace PHPixie\ORM\Mappers;

abstract class Cascade
{
    protected $relationships;
    protected $relationshipMap;
    
    public function __construct($relationships)
    {
        $this->relationships = $relationships;
        $this->relationshipMap = $relationships->map();
    }
    
    protected function getHandledSides($modelName)
    {
        $sides = array();
        foreach($this->relationshipMap->modelSides($modelName) as $side) {
            if($this->isSideHandled($side)) {
                $sides[]= $side;
            }
        }
        return $sides;
    }
    
    public function isModelHandled($modelName)
    {
        $sides = $this->getHandledSides($modelName);
        return count($sides) > 0;
    }
    

    
    abstract protected function isSideHandled($side);
}
