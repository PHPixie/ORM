<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Model extends \PHPixie\ORM\Relaionship\Type\Property
{

    public function query()
    {
        return $this->handler->query($this->side, $this->model);
    }
    
    public function load()
    {
        return $this->handler->loadProperty($this->side, $this->model);
    }

    public function add($items)
    {
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->linkPlan($this->config, $left, $right);
        $plan->execute();
        $this->handler->linkProperties($this->config, $left, $right);
    }

    public function remove($items)
    {
        if ($items === null)
            return;
        list($left, $right) = $this->getSides($items);
        $plan = $this->handler->unlinkPlan($this->config, $left, $right);
        $plan->execute();
        $this->handler->unlinkProperties($this->config, $left, $right);
    }
    
    public function removeAll()
    {
        list($left, $right) = $this->getSides(null);
        $plan = $this->handler->unlinkPlan($this->config, $left, $right);
        $plan->execute();
        if ($this->loaded && $this->value !== null)
            $this->handler->unlinkProperties($this->config, $this->value->usedModels(), null);
            $this->value->removeAll();
        }
    }
    
    protected function getSides($opposing)
    {
        if ($this->side-> type() === 'right')
            return ($this->model, $opposing);
        
        return ($opposing, $this->model);
    }

}
