<?php

namespace PHPixie\ORM\Relationship\Type;

class Handler
{
    protected $orm;
    protected $relationship;

    public function __construct($orm, $relationship)
    {
        $this->orm = $orm;
        $this->relationship = $relationship;
    }

    protected function buildRelatedQuery($modelName, $property, $related)
    {
        return $this->orm->query($modelName)
                                ->related($property, $relatedModel);
    }

}
