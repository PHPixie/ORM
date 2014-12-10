<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity
{

    public function load()
    {
        $owner = parent::load();
        $this->handler->setItemOwner($this->config, $this->entity, $owner);
    }

    public function set($owner)
    {
        $this->processSet($owner, $this->model);
    }
}
