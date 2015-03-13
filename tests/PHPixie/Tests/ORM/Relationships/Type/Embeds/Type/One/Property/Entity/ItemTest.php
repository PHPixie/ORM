<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds\Type\One\Property\Entity;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Property\Entity\Item
 */
class ItemTest extends \PHPixie\Tests\ORM\Relationships\Type\Embeds\Property\EntityTest
{
    protected $config;

    public function setUp()
    {
        $this->config = $this->config();
        parent::setUp();
    }

    /**
     * @covers ::create
     * @covers ::<protected>
     */
    public function testCreate()
    {
        $item = $this->getValue();
        $property = $this->property;
        
        $this->method($this->handler, 'createItem', function() use($property, $item){
            $property->setValue($item);
        }, array($this->entity, $this->config), 0);
        
        $this->assertSame($item, $this->property->create());
    }

    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $item = $this->getValue();
        $this->method($this->handler, 'setItem', null, array($this->entity, $this->config, $item), 0);
        $this->assertSame($this->property, $this->property->set($item));

        $this->prepareRemove();
        $this->assertSame($this->property, $this->property->set(null));
    }
    
    /**
     * @covers ::exists
     * @covers ::<protected>
     */
    public function testExists()
    {
        $value = $this->getValue();
        $this->prepareLoad($value);
        $this->assertSame(true, $this->property->exists());
        
        $this->property->setValue(null);
        $this->assertSame(false, $this->property->exists());
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
     * @covers ::<protected>
     */
    public function testAsData()
    {
        $data = new \stdClass;
        
        $value = $this->getValue();
        $this->prepareLoad($value);
        $this->method($value, 'asObject', $data, array(false), 0);
        $this->assertSame($data, $this->property->asData());

        $this->method($value, 'asObject', $data, array(true), 0);
        $this->assertSame($data, $this->property->asData(true));
    }

    protected function prepareRemove()
    {
        $this->method($this->handler, 'removeItem', null, array($this->entity, $this->config), 0);
    }

    protected function getValue()
    {
        return $this->getEntity();
    }

    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embeds\Type\One\Side\Config');
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\Embeds\Type\One\Property\Entity\Item($this->handler, $this->side, $this->entity);
    }

}
