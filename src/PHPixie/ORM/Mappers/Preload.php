<?php

namespace PHPixie\ORM\Mappers;

class Preload
{
    protected $relationships;
    protected $relationshipMap;
    
    public function __construct($relationships)
    {
        $this->relationships = $relationships;
        $this->relationshipMap = $relationships->map();
    }
    
    public function map($preloadable, $modelName, $preload, $result, $plan)
    {
        
        foreach($preload->properties() as $property) {
            
            $propertyName = $property->propertyName();
            $side = $this->relationshipMap->getSide($modelName, $propertyName);
            
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            
            $preloader = $handler->mapPreload($side, $property, $result, $plan);
            
            $preloadable->addPreloader($propertyName, $preloader);
        }
    }
}