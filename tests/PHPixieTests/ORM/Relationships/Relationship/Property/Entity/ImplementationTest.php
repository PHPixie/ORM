<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Property\Entity;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Property\Entity\Implementation
 */
abstract class ImplementationTest extends \PHPixieTests\ORM\Relationships\Relationship\Property\ImplementationTest
{
    protected $model;
    protected $side;
    protected $value;

    public function setUp()
    {
        $this->model = $this->getModel();
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Property\Model::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Property::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    /**
     * @covers ::model
     * @covers ::<protected>
     */
    public function testModel()
    {
        $this->assertEquals($this->model, $this->property->model());
    }

    /**
     * @covers ::value
     * @covers ::reset
     * @covers ::reload
     * @covers ::setValue
     * @covers ::isLoaded
     * @covers ::__invoke
     * @covers ::<protected>
     */
     public function testValue()
     {
        $property = $this->property;
        $this->assertLoaded(false);
        $this->prepareLoad();
        $this->assertEquals($this->value, $property->value());
        $this->assertEquals($this->value, $property());
        $this->assertLoaded(true, $this->value);
        $property->reset();

        $this->assertLoaded(false);
        $this->prepareLoad();
        $this->assertEquals($this->value, $property());
        $this->assertLoaded(true, $this->value);
        $property->reset();

        $this->assertLoaded(false);
        $value = $this->getValue();
        $this->property->setValue($value);
        $this->assertLoaded(true, $value);
        $this->assertEquals($value, $property());

        $this->prepareLoad();
        $this->assertEquals($this->value, $property->reload());
        $this->assertLoaded(true, $this->value);
        $property->reset();
    }

    protected function assertLoaded($isLoaded, $value = null)
    {
        $this->assertEquals($isLoaded, $this->property->isLoaded());
        if($isLoaded)
            $this->assertEquals($value, $this->property->value());
    }

    abstract protected function getValue();
    abstract protected function value();
    abstract protected function prepareLoad();

}
