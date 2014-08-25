<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model
{
    public function set($owner)
    {
        if($owner === null || $owner->isDeleted())
            return $this->remove();
        
        $config = $this->side->config();
        $plan = $this->handler->linkPlan($config, $owner, $this->model);
        $plan->execute();
        $this->handler->setItemsOwner($config, $owner, $this->model);
        return $this;
    }
    
    public function remove()
    {
        $config = $this->side->config();
        $plan = $this->handler->unlinkItemsPlan($config, $this->model);
        $plan->execute();
        $this->handler->removeItemsOwner($config, $this->model);
        return $this;
    }
    
    public function asData($recursive = true)
    {
        return $this->model->asObject($recursive);
    }
}
