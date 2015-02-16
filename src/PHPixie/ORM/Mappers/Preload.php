<?php

namespace PHPixie\ORM\Mappers;

class Preload
{
    protected $relationships;
    protected $maps;
    
    public function __construct($relationships, $maps)
    {
        $this->relationships = $relationships;
        $this->maps = $maps;
    }
    
    public function map($preloadable, $modelName, $preload, $result, $plan)
    {
        foreach($preload->properties() as $property) {
            
            $propertyName = $property->propertyName();
            $side = $this->maps->preload()->get($modelName, $propertyName);
            
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            
            $preloader = $handler->mapPreload($side, $property, $result, $plan);
            
            $preloadable->addPreloader($propertyName, $preloader);
        }
    }
}