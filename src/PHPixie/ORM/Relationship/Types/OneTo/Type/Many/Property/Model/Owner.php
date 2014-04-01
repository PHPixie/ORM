<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneTo\Type\Many\Property\Model
{
    public function set($owner)
    {
        if ($owner !== null) {
            $plan = $this->handler->linkPlan($this->config, $owner, $this->value);
        }else
            $plan = $this->handler->unlinkItemsPlan($this->config(), $this->value);
        
        $plan->execute();
        $this->handler->setItemsPropertiesOwner($this->config, array($this->value), $owner);
    }
    
}