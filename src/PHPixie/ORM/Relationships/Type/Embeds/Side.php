<?php

namespace PHPixie\ORM\Relationships\Type\Embeds;

abstract class Side extends    \PHPixie\ORM\Relationships\Relationship\Implementation\Side
                    implements \PHPixie\ORM\Relationships\Relationship\Side\Relationship,
                               \PHPixie\ORM\Relationships\Relationship\Side\Preload,
                               \PHPixie\ORM\Relationships\Relationship\Side\Property\Entity
{
    public function modelName()
    {
        return $this->config->ownerModel;
    }

    public function propertyName()
    {
        return $this->config->ownerProperty();
    }
                          
    public function relatedModelName()
    {
        return $this->config->itemModel;
    }
}
