<?php

namespace PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property\Model;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property\Model\Items
 */
class ItemsTest extends \PHPixieTests\ORM\Relationships\Type\Embedded\Type\Embeds\Property\ModelTest
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

        $this->method($this->handler, 'createItem', $item, array($this->model, $this->config), 0);
        $this->assertSame($item, $this->property->create());
    }

    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $item = $this->getValue();
        $this->method($this->handler, 'setItem', null, array($this->model, $this->config, $item), 0);
        $this->assertSame($this->property, $this->property->set($item));

        $this->prepareRemove();
        $this->assertSame($this->property, $this->property->set(null));
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

        $this->prepareLoad();
        $this->method($this->value, 'asObject', $data, array(false), 0);
        $this->assertSame($data, $this->property->asData());

        $this->method($this->value, 'asObject', $data, array(true), 0);
        $this->assertSame($data, $this->property->asData(true));
    }

    protected function prepareLoad()
    {
        $this->value = $this->value();
        $this->method($this->handler, 'loadProperty', $this->value, array($this->config, $this->model), 0);
    }

    protected function prepareRemove()
    {
        $this->method($this->handler, 'removeItem', null, array($this->model, $this->config), 0);
    }

    protected function value()
    {
        return $this->getValue();
    }

    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Property\Model\Item($this->handler, $this->side, $this->model);
    }

    protected function getValue()
    {
        return $this->quickMock('\PHPixie\ORM\Repositories\Type\Embedded\Model');
    }

    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\Embedded\Type\Embeds\Type\One\Side\Config');
    }
}
