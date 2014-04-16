<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model
{
    public function load()
    {
        $owner = parent::load();
        $this->handler->setItemOwner($this->config, $this->model, $owner);
    }
    
    public function set($owner)
    {
        if ($owner !== null) {
            $plan = $this->handler->linkPlan($this->config, $owner, $this->model);
        }else
            $plan = $this->handler->unlinkPlan($this->config, null, $this->model);
        
        $plan->execute();
        $this->handler->setItemsOwner($this->config, $this->model, $owner);
    }
    
}