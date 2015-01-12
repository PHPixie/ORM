<?php

namespace PHPixie\ORM\Sides\Map;

class Entity extends \PHPixie\ORM\Sides\Map
{
    public function property($entity, $propertyName)
    {
        $side = $this->get($entity->modelName(), $propertyName);
        $relationship = $this->relationships->get($side->relationshipType());

        return $relationship->entityProperty($side, $entity);
    }
}