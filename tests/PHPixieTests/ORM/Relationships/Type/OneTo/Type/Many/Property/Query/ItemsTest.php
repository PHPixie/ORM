<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Items
 */
class ItemsTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\QueryTest
{
    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAdd()
    {
        $value = $this->getValue();
        $plan = $this->prepareLinkPlan($value);
        $this->method($plan, 'execute', null, array(), 0);
        $this->prepareResetProperties($value);
        $this->assertSame($this->property, $this->property->set($value));
        
        $this->prepareRemove();
        $this->property->set(null);
    }
    
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->prepareRemove();
        $this->assertSame($this->property, $this->property->remove());
    }
    
    protected function getOwner()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Items($this->handler, $this->side, $this->query);
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