<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Embed extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Model
{
    public function add($items)
    {
        $this->handler->addItems($this->side, $items, $this->model);
    }

    public function remove($items, $reset = true)
    {
        $plan = $this->handler->unlinkItemsPlan($this->side->config(), $items, $this->model);
        $plan->execute();
        if($reset)
            $this->reset();
    }
}
