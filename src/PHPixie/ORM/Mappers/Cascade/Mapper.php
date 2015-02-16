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
    
    protected function assertDirectionalPath($path, $modelName)
    {
        if($path->containsModel($modelName))
            throw new \PHPixie\ORM\Exception\Mapper("Cascade path already contains model $modelName");
    }
}
