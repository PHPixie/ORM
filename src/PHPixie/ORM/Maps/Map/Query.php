<?php

namespace PHPixie\ORM\Maps\Map;

class Query extends \PHPixie\ORM\Maps\Map
{
    public function property($query, $propertyName)
    {
        $side = $this->get($query->modelName(), $propertyName);
        $relationship = $this->relationships->get($side->relationshipType());

        return $relationship->queryProperty($side, $query);
    }
}