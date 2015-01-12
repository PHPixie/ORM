<?php

namespace PHPixie\ORM\Sides\Map;

class Query extends \PHPixie\ORM\Sides\Map
{
    public function property($query, $propertyName)
    {
        $side = $this->get($query->modelName(), $propertyName);
        $relationship = $this->relationships->get($side->relationshipType());

        return $relationship->queryProperty($side, $query);
    }
}