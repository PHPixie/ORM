<?php

namespace PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds;

abstract class Handler extends \PHPixie\ORM\Relationships\Type\Embedded\Handler
{

    public function mapRelationship($side, $query, $group, $plan)
    {
        $builder = $query->getWhereBuilder();
        $this->mapRelationshipBuilder($side, $builder, $group, $plan);
    }

    protected function removeItemFromOwner($item)
    {
        $owner = $item->owner();
        if ($owner !== null) {
            $propertyName = $item->ownerPropertyName();
            $property = $owner->relationshipProperty($propertyName);
            if ($property instanceof \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property\Item) {
                $property->remove();
            } else {
                $property->remove($item);
            }
        }

    }


    protected function fieldPrefix($oldPrefix, $path)
    {
        if ($oldPrefix === null)
            return $path;

        if ($path === null)
            return $oldPrefix;

        return $oldPrefix.'.'.$path;
    }

    public function preload($side, $ownerLoader, $plan)
    {
        $loader = $this->relationshipType->loader($side->config, $ownerLoader);

        return $this->relationshipType->preloader($side, $loader);
    }

    public abstract function mapRelationshipBuilder($side, $builder, $group, $plan, $pathPrefix = '');
}
