<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Query;

class Owner extends \PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Query
{
    public function set($owner)
    {
        if ($owner !== null) {
            $plan = $this->handler->linkPlan($this->config, $owner, $this->query);
        }else
            $plan = $this->handler->unlinkItemsPlan($this->config(), $this->query);
        
        $plan->execute();
        $this->handler->resetOwnerProperty($this->config, $owner);
    }
}
