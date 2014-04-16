<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Model extends \PHPixie\ORM\Relationship\Types\OneToMany\Property
{
    public function query()
    {
        return $this->handler->query($this->side, $this->query);
    }

    public function add($items)
    {
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->linkPlan($this->config, $left, $right);
        $plan->execute();
        $this->handler->resetProperties($this->side, $items);
    }

    public function remove($items)
    {
        if ($items === null)
            return;
        
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->unlinkPlan($this->config, $left, $right);
        $plan->execute();
        $this->handler->resetProperties($this->side, $items);
    }
    
    public function removeAll()
    {
        list($left, $right) = $this->getSides(null);
        $plan = $this->handler->unlinkPlan($this->config, $left, $right);
        $plan->execute();
    }
    
    protected function getSides($opposing)
    {
        if ($this->side-> type() === 'right')
            return ($this->model, $opposing);
        
        return ($opposing, $this->query);
    }
}
