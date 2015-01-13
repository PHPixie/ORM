<?php

namespace PHPixie\ORM\Maps\Map;

class Entity extends \PHPixie\ORM\Maps\Map
{
    public function property($entity, $propertyName)
    {
        $side = $this->get($entity->modelName(), $propertyName);
        $relationship = $this->relationships->get($side->relationshipType());

        return $relationship->entityProperty($side, $entity);
    }
}