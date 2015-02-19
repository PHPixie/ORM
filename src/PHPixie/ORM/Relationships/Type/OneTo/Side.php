<?php

namespace PHPixie\ORM\Relationships\Type\OneTo;

abstract class Side extends    \PHPixie\ORM\Relationships\Relationship\Implementation\Side
                    implements \PHPixie\ORM\Relationships\Relationship\Side\Relationship,
                               \PHPixie\ORM\Relationships\Relationship\Side\Preload,
                               \PHPixie\ORM\Relationships\Relationship\Side\Property\Entity,
                               \PHPixie\ORM\Relationships\Relationship\Side\Property\Query,
                               \PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete
{
    public function modelName()
    {
        if ($this->type === 'owner')
            return $this->config->itemModel;

        return $this->config->ownerModel;
    }

    public function propertyName()
    {
        if ($this->type === 'owner')
            return $this->config->itemOwnerProperty;

        return $this->config->ownerProperty();
    }
                                   
    public function relatedModelName()
    {
        if ($this->type === 'owner')
            return $this->config->ownerModel;
        
        return $this->config->itemModel;
    }
    
    public function isDeleteHandled()
    {
        return $this->type !== 'owner';
    }
}
