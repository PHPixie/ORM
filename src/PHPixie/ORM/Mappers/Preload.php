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
    
    public function map($preloadingProxy, $modelName, $preload, $result, $plan)
    {
        $this->addPreloaders($preloadingProxy, $modelName, $preload, $result, $plan);
    }
    
    public function mapEmbedded($preloadingProxy, $modelName, $preload, $result, $plan, $embeddedPath)
    {
        $this->addPreloaders($preloadingProxy, $modelName, $preload, $result, $plan, $embeddedPath);
    }
    
    protected function addPreloaders($preloadingProxy, $modelName, $preload, $result, $plan, $embeddedPath = null)
    {
        
        foreach($preload->properties() as $property) {
            
            $propertyName = $property->propertyName();
            $side = $this->relationshipMap->getSide($modelName, $propertyName);
            
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            
            if($embeddedPath !== null) {
                $preloader = $handler->mapPreloadEmbedded($side, $property, $result, $plan, $embeddedPath);
            }else{
                $preloader = $handler->mapPreload($side, $property, $result, $plan);
            }
            
            $preloadingProxy->addPreloader($propertyName, $preloader);
        }
    }
}