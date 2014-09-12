<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

class Items extends \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query
{
    public function add($items)
    {
        $plan = $this->handler->linkPlan($this->config, $this->query, $items);
        $plan->execute();
        $this->handler->resetItemsProperties($this->config, $items);
        return $this;
    }

    public function remove($items)
    {
        $plan = $this->handler->unlinkPlan($this->config, $this->query, $items);
        $plan->execute();
        $this->handler->resetItemsProperties($this->config, $items);
        return $this;
    }

    public function removeAll()
    {
        $plan = $this->handler->unlinkItemsPlan($this->config, $this->query, null);
        $plan->execute();
        return $this;
    }
}
