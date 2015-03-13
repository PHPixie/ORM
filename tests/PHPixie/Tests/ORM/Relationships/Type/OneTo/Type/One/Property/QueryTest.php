<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\One\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\Query\SingleTest
{
    
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->method($this->side, 'type', 'owner', array());
        $this->setTest();
    }
    
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSetItem()
    {
        $this->method($this->side, 'type', 'item', array());
        $this->setTest();
    }
    
    protected function prepareLinkPlan($value)
    {
        list($owner, $item) = $this->getSides($value);
        $plan = $this->getPlan();
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $owner, $item), 0);
        return $plan;
    }
    
    protected function prepareResetProperties($value)
    {
        $this->method($this->handler, 'resetProperties', null, array($this->side, $value), 1);
    }
    
    protected function prepareUnlinkPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkPlan', $plan, array($this->side, $this->query), 0);
        return $plan;
    }
    
    protected function getSides($value)
    {
        if ($this->side->type() === 'item')
            return array($this->query, $value);

        return array($value, $this->query);
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Query($this->handler, $this->side, $this->query);
    }
    
    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\One\Side\Config');
    }
}