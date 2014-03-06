<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Side extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Model
{
    public function load()
    {
        return $this->query()->findAll();
    }

    public function add($items, $reset = true)
    {
        $plan = $this->handler->linkPlan($this->link, $this->model, $items);
        $plan->execute();
        if($reset)
            $this->reset();
    }

    public function remove($items, $reset = true)
    {
        $plan = $this->handler->unlinkItemsPlan($this->link, $items, $this->model);
        $plan->execute();
        if($reset)
            $this->reset();
    }

}
