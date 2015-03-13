<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Items
 */
class ItemsTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\EntityTest
{
    
    protected function prepareLoad($value)
    {
        $this->method($this->handler, 'loadItemsProperty', $this->setValueCallback($value), array($this->side, $this->entity), 0);
    }
    
    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAdd()
    {
        $item = $this->getEntity();
        $plan = $this->getPlan();
        $this->method($this->handler, 'linkPlan', $plan, array($this->config, $this->entity, $item), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'addOwnerItems', null, array($this->config, $this->entity, $item), 1);
        $this->assertSame($this->property, $this->property->add($item));
    }
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $item = $this->getEntity();
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkPlan', $plan, array($this->config, $this->entity, $item), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'removeOwnerItems', null, array($this->config, $this->entity, $item), 1);
        $this->assertSame($this->property, $this->property->remove($item));
    }
    
    /**
     * @covers ::removeAll
     * @covers ::<protected>
     */
    public function testRemoveAll()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkOwnersPlan', $plan, array($this->config, $this->entity), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'removeAllOwnerItems', null, array($this->config, $this->entity), 1);
        $this->assertSame($this->property, $this->property->removeAll());
    }
    
    /**
     * @covers ::asData
     * @covers ::<protected>
     */
    public function testAsData()
    {
        $value = new \ArrayObject;
        $this->prepareLoad($value);
        for($i=0;$i<3;$i++){
            $entity = $this->getEntity();
            if($i != 1) {
                $this->method($entity, 'isDeleted', false, array());
                $value[]=$entity;
                $this->method($entity, 'asObject', $i, array(false), 1);
                $this->method($entity, 'asObject', $i, array(true), 3);
                
            }else{
                $this->method($entity, 'isDeleted', true, array());
            }
        }
        
        $this->assertEquals(array(0, 2), $this->property->asData());
        $this->assertEquals(array(0, 2), $this->property->asData(true));
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Entity\Items($this->handler, $this->side, $this->entity);
    }
    
    protected function getValue()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
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