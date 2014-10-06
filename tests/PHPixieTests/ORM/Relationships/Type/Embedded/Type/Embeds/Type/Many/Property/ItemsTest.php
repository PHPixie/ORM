<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property\Model\Items
 */
class ItemsTest extends \PHPixieTests\ORM\Relationships\Relationship\Property\ModelTest
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
        $this->prepareLoad();
        foreach(array(true, false) as $exists) {
            $this->method($this->value, 'offsetExists', $exists, array(2), 0);
            $this->assertEquals($exists, $this->property->offsetExists(2));

            $this->method($this->value, 'offsetExists', $exists, array(3), 0);
            $this->assertEquals($exists, isset($this->property[3]));
        }
    }

    /**
     * @covers ::offsetGet
     * @covers ::<protected>
     */
    public function testOffsetGet()
    {
        $this->prepareLoad();
        $model = $this->getModel();
        $this->method($this->value, 'getByOffset', $model, array(2), 0);
        $this->assertEquals($model, $this->property->offsetGet(2));

        $this->method($this->value, 'getByOffset', $model, array(3), 0);
        $this->assertEquals($model, $this->property[3]);
    }

    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCount()
    {
        $this->prepareLoad();
        $this->method($this->value, 'count', 5, array(), 0);
        $this->assertEquals(5, $this->property->count());
    }

    /**
     * @covers ::offsetSet
     * @covers ::<protected>
     */
    public function testOffsetSet()
    {
        $item = $this->getModel();
        $this->method($this->handler, 'offsetSet', null, array($this->model, $this->config, 2, $item), 0);
        $this->property->offsetSet(2, $item);

        $this->method($this->handler, 'offsetSet', null, array($this->model, $this->config, 3, $item), 0);
        $this->property[3] = $item;
    }

    /**
     * @covers ::offsetUnset
     * @covers ::<protected>
     */
    public function testOffsetUnset()
    {
        $this->method($this->handler, 'offsetUnset', null, array($this->model, $this->config, 2), 0);
        $this->property->offsetUnset(2);

        $this->method($this->handler, 'offsetUnset', null, array($this->model, $this->config, 3), 0);
        unset ( $this->property[3] );
    }

    /**
     * @covers ::create
     * @covers ::<protected>
     */
    public function testCreate()
    {
        $item = $this->getModel();

        $this->method($this->handler, 'offsetCreate', $item, array($this->model, $this->config, null), 0);
        $this->assertSame($item, $this->property->create());

        $this->method($this->handler, 'offsetCreate', $item, array($this->model, $this->config, 3), 0);
        $this->assertSame($item, $this->property->create(3));
    }

    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAdd()
    {
        $item = $this->getModel();
        $this->method($this->handler, 'offsetSet', null, array($this->model, $this->config, null, $item), 0);
        $this->property->add($item);

        $this->method($this->handler, 'offsetSet', null, array($this->model, $this->config, 3, $item), 0);
        $this->property->add($item, 3);
    }

    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $item = $this->getModel();
        $this->method($this->handler, 'removeItems', null, array($this->model, $this->config, $item), 0);
        $this->property->remove($item);
    }

    /**
     * @covers ::removeAll
     * @covers ::<protected>
     */
    public function testRemoveAll()
    {
        $this->method($this->handler, 'removeAllItems', null, array($this->model, $this->config), 0);
        $this->property->removeAll();
    }

    protected function prepareLoad()
    {
        $this->value = $this->value();
        $this->method($this->handler, 'loadProperty', $this->value, array($this->config, $this->model), 0);
    }

    protected function value()
    {
        return $this->getValue();
    }

    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Property\Items($this->handler, $this->side, $this->model);
    }

    protected function getValue()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\Embedded\ArrayNode');
    }


    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\Many\Side\Config');
    }
}
