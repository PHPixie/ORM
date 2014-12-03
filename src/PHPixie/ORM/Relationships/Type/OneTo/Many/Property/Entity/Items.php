<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity;

class Items extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Entity
{
    protected function load()
    {
        return $this->handler->loadItemsProperty($this->side, $this->model);
    }

    public function add($items)
    {
        $config = $this->side->config();
        $plan = $this->handler->linkPlan($config, $this->model, $items);
        $plan->execute();
        $this->handler->addOwnerItems($config, $this->model, $items);
        return $this;
    }

    public function remove($items)
    {
        $config = $this->side->config();
        $plan = $this->handler->unlinkPlan($config, $this->model, $items);
        $plan->execute();
        $this->handler->removeOwnerItems($config, $this->model, $items);
        return $this;
    }

    public function removeAll()
    {
        $config = $this->side->config();
        $plan = $this->handler->unlinkOwnersPlan($config, $this->model);
        $plan->execute();
        $this->handler->removeAllOwnerItems($config, $this->model);
        return $this;
    }

    public function asData($recursive = false)
    {
        $data = array();
        foreach($this->value() as $model)
            $data[] = $model->asObject($recursive);
        return $data;
    }
}
