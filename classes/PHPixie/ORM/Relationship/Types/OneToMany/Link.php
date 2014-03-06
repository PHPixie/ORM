<?php

namespace PHPixie\ORM\Relationships\Types\OneToMany;

class Link extends PHPixie\ORM\Relationship\Link
{
    public function modelName()
    {
        if ($this->type === 'owner') {
            return $this->config->get($this->config->itemsModel);
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
        return 'oneToMany'
    }
}
