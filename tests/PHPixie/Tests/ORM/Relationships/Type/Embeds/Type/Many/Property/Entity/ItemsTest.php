<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\Many\Property\Entity;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Entity\Items
 */
class ItemsTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\Property\EntityTest
{
    protected $config;

    public function setUp()
    {
        $this->config = $this->config();
        parent::setUp();
    }

    /**
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        $value = $this->prepareLoad();
        
        foreach(array(true, false) as $exists) {
            $this->method($value, 'offsetExists', $exists, array(2), 0);
            $this->assertEquals($exists, $this->property->offsetExists(2));

            $this->method($value, 'offsetExists', $exists, array(3), 0);
            $this->assertEquals($exists, isset($this->property[3]));
        }
    }

    /**
     * @covers ::offsetGet
     * @covers ::<protected>
     */
    public function testOffsetGet()
    {
        $value = $this->prepareLoad();
        
        $entity = $this->getEntity();
        $this->method($value, 'getByOffset', $entity, array(2), 0);
        $this->assertEquals($entity, $this->property->offsetGet(2));

        $this->method($value, 'getByOffset', $entity, array(3), 0);
        $this->assertEquals($entity, $this->property[3]);
    }

    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCount()
    {
        $value = $this->prepareLoad();
        
        $this->method($value, 'count', 5, array(), 0);
        $this->assertEquals(5, $this->property->count());
    }

    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSet()
    {
        $item = $this->getEntity();
        $this->method($this->handler, 'offsetSet', null, array($this->entity, $this->config, 2, $item), 0);
        $this->property->offsetSet(2, $item);

        $this->method($this->handler, 'offsetSet', null, array($this->entity, $this->config, 3, $item), 0);
        $this->property[3] = $item;
    }

    /**
     * @covers ::offsetUnset
     * @covers ::<protected>
     */
    public function testOffsetUnset()
    {
        $this->method($this->handler, 'offsetUnset', null, array($this->entity, $this->config, 2), 0);
        $this->property->offsetUnset(2);

        $this->method($this->handler, 'offsetUnset', null, array($this->entity, $this->config, 3), 0);
        unset ( $this->property[3] );
    }

    /**
     * @covers ::create
     * @covers ::<protected>
     */
    public function testCreate()
    {
        $item = $this->getEntity();

        $this->method($this->handler, 'offsetCreate', $item, array($this->entity, $this->config, null, null), 0);
        $this->assertSame($item, $this->property->create());

        $data = array('t' => 1);
        $this->method($this->handler, 'offsetCreate', $item, array($this->entity, $this->config, 3, $data), 0);
        $this->assertSame($item, $this->property->create($data, 3));
    }

    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAdd()
    {
        $item = $this->getEntity();
        $this->method($this->handler, 'offsetSet', null, array($this->entity, $this->config, null, $item), 0);
        $this->assertSame($this->property, $this->property->add($item));

        $this->method($this->handler, 'offsetSet', null, array($this->entity, $this->config, 3, $item), 0);
        $this->assertSame($this->property, $this->property->add($item, 3));
    }

    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $item = $this->getEntity();
        $this->method($this->handler, 'removeItems', null, array($this->entity, $this->config, $item), 0);
        $this->assertSame($this->property, $this->property->remove($item));
    }

    /**
     * @covers ::removeAll
     * @covers ::<protected>
     */
    public function testRemoveAll()
    {
        $this->method($this->handler, 'removeAllItems', null, array($this->entity, $this->config), 0);
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
            $value[]=$entity;
            
            $this->method($entity, 'asObject', $i, array(false), 0);
            $this->method($entity, 'asObject', $i, array(true), 1);
        }
        
        $this->assertEquals(array(0, 1, 2), $this->property->asData());
        $this->assertEquals(array(0, 1, 2), $this->property->asData(true));
    }
    
    protected function prepareLoad($value = null)
    {
        if($value === null) {
            $value = $this->getValue();
        }
        
        parent::prepareLoad($value);
        
        return $value;
    }
    
    protected function getValue()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Embedded\ArrayNode');
    }
    
    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Side\Config');
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\Many\Property\Entity\Items($this->handler, $this->side, $this->entity);
    }
}
