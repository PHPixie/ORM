<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Owner
 */
class OwnerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\Model\SingleTest
{
    protected function prepareLinkPlan($owner)
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $owner, $this->model), 0);
        return $plan;
    }
    
    protected function prepareSetProperties($owner)
    {
        $this->method($this->handler, 'setItemsOwner', null, array($this->config, $owner, $this->model), 1);
    }
    
    protected function prepareUnlinkPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkItemsPlan', $plan, array($this->config, $this->model), 0);
        return $plan;
    }
    
    protected function prepareUnsetProperties()
    {
        $this->method($this->handler, 'removeItemsOwner', null, array($this->config, $this->model), 1);
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Owner($this->handler, $this->side, $this->model);
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