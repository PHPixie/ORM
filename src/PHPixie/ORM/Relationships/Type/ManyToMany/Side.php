<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany;

class Side extends    \PHPixie\ORM\Relationships\Relationship\Implementation\Side
           implements \PHPixie\ORM\Relationships\Relationship\Side\Relationship,
                      \PHPixie\ORM\Relationships\Relationship\Side\Property\Entity,
                      \PHPixie\ORM\Relationships\Relationship\Side\Property\Query,
                      \PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete
{
    public function modelName()
    {
        if ($this->type === 'left')
            return $this->config->leftModel;

        return $this->config->rightModel;
    }

    public function propertyName()
    {
        if ($this->type === 'left')
            return $this->config->leftProperty;

        return $this->config->rightProperty;
    }

    public function relationshipType()
    {
        return 'manyToMany';
    }
                          
    public function relatedModelName()
    {
        if ($this->type === 'left')
            return $this->config->rightModel;

        return $this->config->leftModel;
    }
    
    public function isDeleteHandled()
    {
        return true;
    }
    
 }