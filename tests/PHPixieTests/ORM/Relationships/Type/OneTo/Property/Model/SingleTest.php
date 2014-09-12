<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Property\Model\Single
 */
abstract class SingleTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Property\ModelTest
{
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $value = $this->getValue();
        $this->method($value, 'isDeleted', false, array(), 0);
        $plan = $this->prepareLinkPlan($value);
        
        $this->method($plan, 'execute', null, array(), 0);
        $this->prepareSetProperties($value);

        $this->assertSame($this->property, $this->property->set($value));
        
        $this->prepareRemove();
        $this->property->set(null);
        
        $this->method($value, 'isDeleted', true, array(), 0);
        $this->prepareRemove();
        $this->property->set($value);
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
    
    /**
     * @covers ::asData
     */
    public function testAsData()
    {
        $data = new \stdClass;
        
        $this->prepareLoad();
        $this->method($this->value, 'isDeleted', false, array(), 0);
        $this->method($this->value, 'asObject', $data, array(true), 1);
        $this->assertSame($data, $this->property->asData());
        
        $this->method($this->value, 'asObject', $data, array(false), 1);
        $this->assertSame($data, $this->property->asData(false));
    }
    
    protected function prepareRemove()
    {
        $plan = $this->prepareUnlinkPlan();
        $this->method($plan, 'execute', null, array(), 0);
        $this->prepareUnsetProperties();
    }
    
    protected function getValue()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
    }
    
    abstract protected function prepareLinkPlan($value);
    abstract protected function prepareSetProperties($value);
    abstract protected function prepareUnlinkPlan();
    abstract protected function prepareUnsetProperties();
}