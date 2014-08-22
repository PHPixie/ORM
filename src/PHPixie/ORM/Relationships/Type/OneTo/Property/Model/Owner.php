<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property\Model;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Model
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
        $config = $this->side->config();
        $plan = $this->handler->setOwnerPlan($config, $this->model, $owner);
        $plan->execute();
        $this->handler->setPropertyOwner($config, $this->model, $owner);
    }
    
    public function remove()
    {
        $config = $this->side->config();
        $plan = $this->handler->removeOwnerPlan($config, $this->model);
        $plan->execute();
        $this->handler->removePropertyOwner($config, $this->model, $owner);
    }
    
    public function asData($recursive = true)
    {
        return $model->asObject($recursive);
    }

}
