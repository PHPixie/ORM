<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Property\Query;

abstract class Single extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Query
{
    public function set($value)
    {
        if($value === null || $value->isDeleted())
            return $this->remove();
        
        $plan = $this->linkPlan($value);
        $plan->execute();
        $this->resetProperties($value);
        return $this;
    }

    public function remove()
    {
        $plan = $this->unlinkPlan();
        $plan->execute();
        return $this;
    }
    
    protected abstract function linkPlan($value);
    protected abstract function resetProperties($value);
    protected abstract function unlinkPlan();
}