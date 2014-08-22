<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model
{
    public function query()
    {
        return $this->handler->query($this->side, $this->model);
    }

    protected function load()
    {
        return $this->handler->loadProperty($this->side, $this->model);
    }
    
    public function set($owner)
    {
        if($owner === null && $owner->isDeleted())
            return $this->remove();
        
        $config = $this->side->config();
        $plan = $this->handler->setOwnerPlan($config, $this->model, $owner);
        $plan->execute();
        $this->handler->setItemOwner($config, $this->model, $owner);
    }
    
    public function remove()
    {
        $config = $this->side->config();
        $plan = $this->handler->removeOwnerPlan($config, $this->model);
        $plan->execute();
        $this->handler->removeItemOwner($config, $this->model, $owner);
    }
    
    public function asData($recursive = true)
    {
        return $model->asObject($recursive);
    }
}
