<?php

namespace PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property;

abstract class Model extends \PHPixie\ORM\Relationships\Type\OneTo\Property\Model
{
    protected function processSet($owner, $item)
    {
        if ($owner !== null && $item !== null) {
            $plan = $this->handler->linkPlan($this->config, $owner, $item);
        }else
            $plan = $this->handler->unlinkPlan($this->config, $owner, null);

        $plan->execute();
        $this->handler->setItemOwner($this->config, $item, $owner);
    }


}
