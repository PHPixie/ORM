<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Items
 */
class ItemsTest extends \PHPixieTests\ORM\Relationships\Type\OneTo\Type\Many\Property\ModelTest
{
    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAdd()
    {
        $item = $this->getModel();
        $plan = $this->getPlan();
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $this->model, $item), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'setItemsOwner', null, array($this->config, $this->model, $item), 1);
        $this->assertSame($this->property, $this->property->add($item));
    }
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $item = $this->getModel();
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkPlan', $plan, array($this->config, $this->model, $item), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'removeOwnerItems', null, array($this->config, $this->model, $item), 1);
        $this->assertSame($this->property, $this->property->remove($item));
    }
    
    /**
     * @covers ::removeAll
     * @covers ::<protected>
     */
    public function testRemoveAll()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkOwnersPlan', $plan, array($this->config, $this->model), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'removeAllOwnerItems', null, array($this->config, $this->model), 1);
        $this->assertSame($this->property, $this->property->removeAll());
    }
    
    /**
     * @covers ::asData
     * @covers ::<protected>
     */
    public function testAsData()
    {
        $this->prepareLoad(new \ArrayObject);
        for($i=0;$i<3;$i++){
            $model = $this->quickMock('stdClass', array('asObject'));
            $this->value[]=$model;
            $this->method($model, 'asObject', $i, array(true), 0);
            $this->method($model, 'asObject', $i, array(false), 1);
        }
        
        $this->assertEquals(array(0, 1, 2), $this->property->asData());
        $this->assertEquals(array(0, 1, 2), $this->property->asData(false));
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Model\Items($this->handler, $this->side, $this->model);
    }
    
    protected function getValue()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
    }
}