<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\One\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity
 */
class EntityTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\Entity\SingleTest
{
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        foreach(array('owner', 'item') as $type) {
            $this->prepareSide($type);
            $this->setTest();
        }
    }
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
         foreach(array('owner', 'item') as $type) {
            $this->prepareSide($type);
            $this->prepareRemove();
            $this->assertSame($this->property, $this->property->remove());
        }
    }
    
    protected function prepareLoad($value)
    {
        $this->method($this->handler, 'loadProperty', $this->setValueCallback($value), array($this->side, $this->entity), 0);
    }
    
    protected function prepareLinkPlan($value)
    {
        $plan = $this->getPlan();
        list($owner, $item) = $this->getSides($value);
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $owner, $item), 0);
        return $plan;
    }
    
    protected function prepareSetProperties($value)
    {
        $plan = $this->getPlan();
        list($owner, $item) = $this->getSides($value);
        $this->method($this->handler, 'linkProperties', null, array($this->config, $owner, $item), 1);
    }
    
    protected function prepareUnlinkPlan()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkPlan', $plan, array($this->side, $this->entity), 0);
        return $plan;
    }
    
    protected function prepareUnsetProperties()
    {
        $this->method($this->handler, 'unlinkProperties', null, array($this->side, $this->entity), 1);
    }
    
    protected function prepareSide($type) {
        $this->method($this->side, 'type', $type, array());
    }
    
    protected function getSides($value)
    {
        if ($this->side->type() === 'item')
            return array($this->entity, $value);

        return array($value, $this->entity);
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\One\Property\Entity($this->handler, $this->side, $this->entity);
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