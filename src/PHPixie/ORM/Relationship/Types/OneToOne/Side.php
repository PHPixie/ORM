<?php

namespace PHPixie\ORM\Relationships\Types\OneToMany;

class Side extends PHPixie\ORM\Relationship\Side
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

    public function relationship()
    {
        return 'oneToOne'
    }
}
