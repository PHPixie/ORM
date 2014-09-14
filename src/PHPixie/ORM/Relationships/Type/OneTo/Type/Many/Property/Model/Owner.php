<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Model\Single
{
    protected function linkPlan($owner)
    {
        return $this->handler->linkPlan($this->side->config(), $owner, $this->model);
    }
    
    protected function setProperties($owner)
    {
        return $this->handler->addOwnerItems($this->side->config(), $owner, $this->model);
    }
    
    protected function unlinkPlan()
    {
        return $this->handler->unlinkItemsPlan($this->side->config(), $this->model);
    }
    
    protected function unsetProperties()
    {
        return $this->handler->removeItemOwner($this->side->config(), $this->model);
    }
}
