<?php

namespace PHPixie\ORM\Mappers\Cascade;

abstract class Mapper
{
    protected $mappers;
    protected $relationships;
    protected $cascadeMap;
    
    public function __construct($mappers, $relationships, $cascadeMap)
    {
        $this->mappers       = $mappers;
        $this->relationships = $relationships;
        $this->cascadeMap    = $cascadeMap;
    }
    
    protected function assertDirectionalPath($path, $modelName)
    {
        if($path->containsModel($modelName))
            throw new \PHPixie\ORM\Exception\Mapper("Cascade path already contains model $modelName");
    }
    
    public function isModelHandled($modelName)
    {
        return $this->cascadeMap->hasModelSides($modelName);
    }
}
