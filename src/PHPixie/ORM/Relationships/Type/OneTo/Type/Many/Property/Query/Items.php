<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

class Items extends \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query
{
    public function add($items)
    {
        $plan = $this->handler->linkPlan($this->config, $this->query, $items);
        $plan->execute();
        $this->handler->resetItemsProperties($this->config, $items);
    }

    public function remove($items)
    {
        if ($items === null)
            return;

        $plan = $this->handler->unlinkPlan($this->config, $this->query, $items);
        $plan->execute();
        $this->handler->resetItemsProperties($this->config, $items);
    }

    public function removeAll()
    {
        $plan = $this->handler->unlinkPlan($this->config, $this->query, null);
        $plan->execute();
    }
}
