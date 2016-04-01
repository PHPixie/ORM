<?php

namespace PHPixie\ORM\Mappers;

class Preload
{
    protected $relationships;
    protected $preloadMap;
    
    public function __construct($relationships, $preloadMap)
    {
        $this->relationships = $relationships;
        $this->preloadMap = $preloadMap;
    }
    
    public function map($preloadable, $modelName, $preload, $result, $plan, $loader)
    {
        foreach($preload->properties() as $property) {
            
            $propertyName = $property->propertyName();
            $side = $this->preloadMap->get($modelName, $propertyName);
            
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            
            $preloader = $handler->mapPreload($side, $property, $result, $plan, $loader);
            
            $preloadable->addPreloader($propertyName, $preloader);
        }
    }
}