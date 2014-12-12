<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

class Items extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Query
{
    public function add($items)
    {
        $plan = $this->handler->linkPlan($this->side->config(), $this->query, $items);
        $plan->execute();
        $this->handler->resetProperties($this->side, $items);
        return $this;
    }

    public function remove($items)
    {
        $plan = $this->handler->unlinkPlan($this->side->config(), $this->query, $items);
        $plan->execute();
        $this->handler->resetProperties($this->side, $items);
        return $this;
    }

    public function removeAll()
    {
        $plan = $this->handler->unlinkItemsPlan($this->side->config(), $this->query, null);
        $plan->execute();
        return $this;
    }
}
