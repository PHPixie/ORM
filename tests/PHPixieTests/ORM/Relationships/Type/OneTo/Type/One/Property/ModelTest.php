<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\One\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Model
 */
class ModelTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\Model\SingleTest
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
    
    protected function prepareSetProperties($value)
    {
        list($owner, $item) = $this->getSides($value);
        $this->method($this->handler, 'linkProperties', null, array($this->config, $owner, $item), 1);
    }
    
    protected function getSides($value)
    {
        if ($this->side->type() === 'item')
            return array($this->model, $value);

        return array($value, $this->model);
    }
    
    protected function prepareUnlinkPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkPlan', $plan, array($this->side, $this->model), 0);
        return $plan;
    }
    
    protected function prepareUnsetProperties()
    {
        $this->method($this->handler, 'unlinkProperties', null, array($this->side, $this->model), 1);
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Model($this->handler, $this->side, $this->model);
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