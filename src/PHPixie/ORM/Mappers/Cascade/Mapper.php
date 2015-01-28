<?php

namespace PHPixie\ORM\Mappers\Cascade;

abstract class Mapper
{
    protected $mappers;
    protected $relationships;
    protected $maps;
    
    public function __construct($mappers, $relationships, $maps)
    {
        $this->mappers = $mappers;
        $this->relationships = $relationships;
        $this->maps = $maps;
    }
    
    protected function getHandledSides($modelName)
    {
        $sides = array();
        foreach($this->maps->entity()->getModelSides($modelName) as $side) {
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
    
    protected function assertDirectionalPath($path, $modelName)
    {
        if($path->containsModel($modelName))
            throw new \PHPixie\ORM\Exception\Mapper("Cascade path already contains model $modelName");
    }
    
    abstract protected function isSideHandled($side);
}
