<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Owner
 */
class OwnerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\Entity\SingleTest
{
    
    protected function prepareLoad($value)
    {
        $property = $this->property;
        
        $this->method($this->handler, 'loadOwnerProperty', $this->setValueCallback($value), array($this->side, $this->entity), 0);
    }
    
    protected function prepareLinkPlan($owner)
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $owner, $this->entity), 0);
        return $plan;
    }
    
    protected function prepareSetProperties($owner)
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'addOwnerItems', null, array($this->config, $owner, $this->entity), 1);
    }
    
    protected function prepareUnlinkPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkItemsPlan', $plan, array($this->config, $this->entity), 0);
        return $plan;
    }
    
    protected function prepareUnsetProperties()
    {
        $this->method($this->handler, 'removeItemOwner', null, array($this->config, $this->entity), 1);
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Owner($this->handler, $this->side, $this->entity);
    }
    
    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }
}