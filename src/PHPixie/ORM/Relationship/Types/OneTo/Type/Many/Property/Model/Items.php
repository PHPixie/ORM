<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model;

class Items extends \PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model {

    public function load()
    {
        return $this->query()->findAll();
    }
    
    public function add($items)
    {
        $plan = $this->handler->linkPlan($this->config, $this->model, $items);
        $plan->execute();
        $this->handler->setItemsPropertiesOwner($this->config, $items, $this->model);
    }

    public function remove($items)
    {
        $plan = $this->handler->unlinkItemsPlan($this->config, $items, $this->model);
        $plan->execute();
        $this->handler->setItemsPropertiesOwner($this->config, $items, null);
        $this->reset();
    }
    
    public function removeAll()
    {
        $plan = $this->handler->unlinkOwnerPlan($this->config, $this->model);
        $plan->execute();
        if ($this->loaded && $this->value !== null)
            $this->handler->setItemsPropertiesOwner($this->config, $this->value, null);
        $this->reset();
    }
    
}