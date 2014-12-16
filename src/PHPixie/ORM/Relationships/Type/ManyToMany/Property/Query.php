<?php

namespace PHPixie\ORM\Relationships\Type\ManyToMany\Property;

class Query extends \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Query
{
    public function query()
    {
        return $this->handler->query($this->side, $this->query);
    }

    public function add($items)
    {
        $config = $this->side->config();
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->linkPlan($config, $left, $right);
        $plan->execute();
        $this->handler->resetProperties($this->side, $items);
        return $this;
    }

    public function remove($items)
    {
        $config = $this->side->config();
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->unlinkPlan($config, $left, $right);
        $plan->execute();
        $this->handler->resetProperties($this->side, $items);
        return $this;
    }

    public function removeAll()
    {
        $plan = $this->handler->unlinkAllPlan($this->side, $this->query);
        $plan->execute();
        return $this;
    }

    protected function getSides($opposing)
    {
        if ($this->side->type() === 'right')
            return array($this->query, $opposing);

        return array($opposing, $this->query);
    }
}
