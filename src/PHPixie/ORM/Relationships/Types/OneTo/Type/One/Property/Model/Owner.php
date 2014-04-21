<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\One\Property\Model;

class Owner extends \PHPixie\ORM\Relationships\Types\OneTo\Type\One\Property\Model
{

    public function load()
    {
        $owner = parent::load();
        $this->handler->setItemOwner($this->config, $this->model, $owner);
    }

    public function set($owner)
    {
        $this->processSet($owner, $this->model);
    }
}
