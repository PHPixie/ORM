<?php

namespace PHPixie\ORM\Relationships\Types\OneTo;

abstract class Side extends PHPixie\ORM\Relationship\Side
{
    public function modelName()
    {
        if ($this->type === 'owner') {
            return $this->config->get($this->config->itemModel);
        } else {
            return $this->config->get($this->config->ownerModel);
        }
    }

    public function propertyName()
    {
        return $this->config->get($this->type.'_property');
    }

    abstract public function relationship();
}
