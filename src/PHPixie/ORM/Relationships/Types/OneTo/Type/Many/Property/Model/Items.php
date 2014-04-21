<?php

namespace PHPixie\ORM\Relationships\Types\OneTo\Type\Many\Property\Model;

class Items extends \PHPixie\ORM\Relationships\Types\OneTo\Type\Many\Property\Model
{
    public function add($items)
    {
        $plan = $this->handler->linkPlan($this->config, $this->model, $items);
        $plan->execute();
        $this->handler->setItemsOwner($this->config, $items, $this->model);
    }

    public function remove($items)
    {
        if ($items === null)
            return;

        $plan = $this->handler->unlinkPlan($this->config, $this->model, $items);
        $plan->execute();
        $this->handler->setItemsOwner($this->config, $items, null, $this->model);
    }

    public function removeAll()
    {
        $plan = $this->handler->unlinkOwnerPlan($this->config, $this->model);
        $plan->execute();
        if ($this->loaded && $this->value !== null)
            $this->handler->setItemsOwner($this->config, $this->value->usedModels(), null, $this->model);
            $this->value->removeAll();
        }
    }

    public function data($recursive = true)
    {
        $data = array();
        foreach($this->value() as $model)
            $data[] = $model->asObject($recursive);

        return $data;
    }
}
