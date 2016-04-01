<?php

namespace PHPixie\ORM\Relationships\Type\Embeds;

abstract class Handler extends    \PHPixie\ORM\Relationships\Relationship\Implementation\Handler
                       implements \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database,
                                  \PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Embedded,
                                  \PHPixie\ORM\Relationships\Relationship\Handler\Preloading
{
    public function mapPreload($side, $property, $result, $plan, $loader)
    {
        $config = $side->config();
        $preloadResult = $this->relationship->preloadResult($result, $config->path);
        
        $preloader = $this->relationship->preloader();
        
        $this->mappers->preload()->map(
            $preloader,
            $config->itemModel,
            $property->preload(),
            $preloadResult,
            $plan,
            $loader
        );
        
        return $preloader;
    }

    public function mapDatabaseQuery($query, $side, $group, $plan)
    {
        $this->mapConditionBuilder($query, $side, $group, $plan);
    }
    
    public function mapEmbeddedContainer($container, $side, $group, $plan)
    {
        $this->mapConditionBuilder($container, $side, $group, $plan);
    }
    
    protected function removeItemFromOwner($item)
    {
        $owner = $item->owner();
        if ($owner !== null) {
            $propertyName = $item->ownerPropertyName();
            $property = $owner->getRelationshipProperty($propertyName);
            if ($property instanceof Type\One\Property\Entity\Item) {
                $property->remove();
            } else {
                $property->remove($item);
            }
        }
    }
    
    abstract protected function mapConditionBuilder($builder, $side, $group, $plan);
}
