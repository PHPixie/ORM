<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Query;

class Items extends \PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Query
{
    public function add($items)
    {
        $plan = $this->handler->linkPlan($this->config, $this->query, $items);
        $plan->execute();
        $this->handler->resetItemsProperties($this->config, $items);
    }

    public function remove($items)
    {
        $plan = $this->handler->unlinkPlan($this->config, $this->query, $items);
        $plan->execute();
        $this->handler->resetItemsProperties($this->config, $items);
    }
    
    public function removeAll()
    {
        $plan = $this->handler->unlinkOwnerPlan($this->side->config(), $this->query);
        $plan->execute();
    }
}
