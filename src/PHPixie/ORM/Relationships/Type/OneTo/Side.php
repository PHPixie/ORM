<?php

namespace PHPixie\ORM\Relationships\Type\OneTo;

abstract class Side extends \PHPixie\ORM\Relationships\Relationship\Implementation\Side
                    implements \PHPixie\ORM\Relationships\Relationship\Side\Cascade\Delete
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
            return $this->config->ownerProperty();

        return $this->config->itemOwnerProperty;
    }

    public function isDeleteHandled()
    {
        return $this->type !== 'owner';
    }
}
