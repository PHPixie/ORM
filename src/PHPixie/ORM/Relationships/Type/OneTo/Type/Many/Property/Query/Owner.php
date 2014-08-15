<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

class Owner extends \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query
{
    public function set($owner)
    {
        if ($owner !== null) {
            $plan = $this->handler->linkPlan($this->config, $owner, $this->query);
        }else
            $plan = $this->handler->unlinkPlan($this->config, null, $this->query);

        $plan->execute();
        $this->handler->resetOwnerProperty($this->config, $owner);
    }
}
