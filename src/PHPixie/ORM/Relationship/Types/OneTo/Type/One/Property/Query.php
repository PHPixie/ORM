<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\One\Property;

abstract class Model extends \PHPixie\ORM\Relationship\Type\Property\Model
{
	protected function processSet($owner, $item)
    {
        if ($owner !== null && $item !== null ) {
            $plan = $this->handler->linkPlan($this->config, $owner, $item);
        }else
            $plan = $this->handler->unlinkPlan($this->config, $owner, null);
        
        $plan->execute();
        $this->handler->setItemOwner($this->config, $item, $owner);
    }
}