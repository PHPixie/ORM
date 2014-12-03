<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Entity\Single
{
    protected function load()
    {
        return $this->handler->loadOwnerProperty($this->side, $this->model);
    }
    
    protected function linkPlan($owner)
    {
        return $this->handler->linkPlan($this->side->config(), $owner, $this->model);
    }

    protected function setProperties($owner)
    {
        $this->handler->addOwnerItems($this->side->config(), $owner, $this->model);
    }

    protected function unlinkPlan()
    {
        return $this->handler->unlinkItemsPlan($this->side->config(), $this->model);
    }

    protected function unsetProperties()
    {
        $this->handler->removeItemOwner($this->side->config(), $this->model);
    }
}
