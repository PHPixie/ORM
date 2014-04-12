<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Property\Model;

class Owner extends PHPixie\ORM\Relationship\Types\Embeds\Property\Model
{

    public function load()
    {
        throw new \PHPixie\ORM\Exception\Model("Owner property can not be loaded, only set");
    }
    
    public function set($owner)
    {
        $this->handler->embedModel($this->embedConfig, $owner, $this->model);
        $this->handler->setOwnerProperty($this->embedConfig, $this->model, $owner);
    }
}