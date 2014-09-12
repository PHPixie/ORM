<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Owner
 */
class OwnerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\Query\SingleTest
{
    protected function prepareLinkPlan($owner)
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $owner, $this->query), 0);
        return $plan;
    }
    
    protected function prepareResetProperties($owner)
    {
        $this->method($this->handler, 'resetItemsOwner', null, array($this->config, $owner), 1);
    }
    
    protected function prepareUnlinkPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkItemsPlan', $plan, array($this->config, $this->query), 0);
        return $plan;
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Owner($this->handler, $this->side, $this->query);
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