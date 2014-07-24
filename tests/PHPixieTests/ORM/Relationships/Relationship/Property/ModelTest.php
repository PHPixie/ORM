<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Property\Model
 */
abstract class ModelTest extends \PHPixieTests\ORM\Relationships\Relationship\PropertyTest
{
    protected $model;
    protected $value;

    public function setUp()
    {
        $this->model = $this->getModel();
        parent::setUp();
    }

    /**
     * @covers ::construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Property\Model::construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Property::construct
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
         $this->assertLoaded(false);
         $this->prepareLoad();
         $this->assertEquals($this->value, $this->property->value());
         $this->assertEquals($this->value, $this->property());
         $this->assertLoaded(true, $this->value);
         $this->reset();

         $this->assertLoaded(false);
         $this->prepareLoad();
         $this->assertEquals($this->value, $this->property());
         $this->assertLoaded(true, $this->value);
         $this->reset();

         $this->assertLoaded(false);
         $value = $this->getValue();
         $this->property->setValue($value);
         $this->assertLoaded(true, $value);
         $this->assertEquals($value, $this->property());

         $this->assertEquals($this->value, $this->property->reload());
         $this->assertLoaded(true, $this->value);
         $this->reset();
     }

      protected function assertLoaded($isLoaded, $value = null)
      {
          $this->assertEquals($isLoaded, $this->property->isLoaded());
          $this->assertEquals($value, $this->property->value());
      }

      abstract protected function getValue();
      abstract protected function value();

}
