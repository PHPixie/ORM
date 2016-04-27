<?php

namespace PHPixie\ORM\Relationships\Type\NestedSet;

class Side extends    \PHPixie\ORM\Relationships\Relationship\Implementation\Side
           implements \PHPixie\ORM\Relationships\Relationship\Side\Relationship,
                      \PHPixie\ORM\Relationships\Relationship\Side\Preload,
                      \PHPixie\ORM\Relationships\Relationship\Side\Property\Query,
                      \PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete
{
    public function modelName()
    {
        return $this->config->model;
    }

    public function propertyName()
    {
        $property = $this->type.'Property';
        return $this->config->$property;
    }

    public function relationshipType()
    {
        return 'nestedSet';
    }
                          
    public function relatedModelName()
    {
        return $this->config->model;
    }
    
    public function isDeleteHandled()
    {
        return $this->type === 'children';
    }
    
 }
