<?php

namespace PHPixie\ORM\Maps\Map\Property;

class Query extends \PHPixie\ORM\Maps\Map\Property
{
    public function property($query, $propertyName)
    {
        $side = $this->get($query->modelName(), $propertyName);
        $relationship = $this->relationships->get($side->relationshipType());

        return $relationship->queryProperty($side, $query);
    }
}