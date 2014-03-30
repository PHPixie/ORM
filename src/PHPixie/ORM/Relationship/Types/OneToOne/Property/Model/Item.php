<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Item extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Model
{
    public function load()
    {
        return $this->query()->find();
    }

    public function set($item)
    {
        $plan = $this->handler->linkPlan($this->side->config(), $this->model, $item);
        $plan->execute();
        $this->setValue($item);
    }

    public function unlink()
    {
        $plan = $this->handler->unlinkItemsPlan($this->side->config(), $items, $this->model);
        $plan->execute();
        $this->setValue(null);
    }

}
