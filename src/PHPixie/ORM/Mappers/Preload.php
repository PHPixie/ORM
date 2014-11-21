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
    
    public function mapPreload($preloadingProxy, $modelName, $preload, $result, $plan)
    {
        $this->addPreloaders($preloadingProxy, $modelName, $preload, $result, $plan);
    }
    
    public function mapPreloadEmbedded($preloadingProxy, $modelName, $preload, $result, $plan, $embeddedPrefix)
    {
        $this->addPreloaders($preloadingProxy, $modelName, $preload, $result, $plan, $embeddedPrefix);
    }
    
    protected function addPreloaders($preloadingProxy, $modelName, $preload, $result, $plan, $embeddedPrefix = null)
    {
        
        foreach($preload->properties() as $property) {
            
            $propertyName = $property->propertyName();
            $side = $this->relationshipMap->getSide($modelName, $propertyName);
            
            $relationship = $this->relationships->get($side->relationshipType());
            $handler = $relationship->handler();
            
            if($embeddedPrefix !== null) {
                $preloader = $handler->mapPreloadEmbedded($side, $property, $result, $plan, $embeddedPrefix);
            }else{
                $preloader = $handler->mapPreload($side, $property, $result, $plan);
            }
            
            $preloadingProxy->addPreloader($propertyName, $preloader);
        }
    }
}