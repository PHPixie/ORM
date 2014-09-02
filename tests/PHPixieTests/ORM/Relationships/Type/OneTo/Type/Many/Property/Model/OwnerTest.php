<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Owner
 */
class OwnerTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\ModelTest
{
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $owner = $this->getValue();
        $this->method($owner, 'isDeleted', false, array(), 0);
        $plan = $this->getPlan();
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $owner, $this->model), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'setItemsOwner', null, array($this->config, $owner, $this->model), 1);
        $this->assertSame($this->property, $this->property->set($owner));
        
        $this->prepareRemove();
        $this->property->set(null);
        
        $this->method($owner, 'isDeleted', true, array(), 0);
        $this->prepareRemove();
        $this->property->set($owner);
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
        
        $this->method($this->model, 'asObject', $data, array(true), 0);
        $this->assertSame($data, $this->property->asData());
        
        $this->method($this->model, 'asObject', $data, array(false), 0);
        $this->assertSame($data, $this->property->asData(false));
    }
    
    protected function prepareRemove()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkItemsPlan', $plan, array($this->config, $this->model), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'removeItemsOwner', null, array($this->config, $this->model), 1);
    }
    
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Owner($this->handler, $this->side, $this->model);
    }
    
    protected function getValue()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database\Model');
    }
}