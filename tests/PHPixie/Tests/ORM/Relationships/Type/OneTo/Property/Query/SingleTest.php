<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Property\Query\Single
 */
abstract class SingleTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\QueryTest
{
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->setTest();
    }
    
    protected function setTest()
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
    
    protected function prepareRemove()
    {
        $plan = $this->prepareUnlinkPlan();
        $this->method($plan, 'execute', null, array(), 0);
    }
    
    protected function getValue()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    abstract protected function prepareLinkPlan($value);
    abstract protected function prepareResetProperties($value);
    abstract protected function prepareUnlinkPlan();
}