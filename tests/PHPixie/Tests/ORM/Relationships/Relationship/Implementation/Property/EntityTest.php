<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity\Implementation
 */
abstract class EntityTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\PropertyTest
{
    protected $entity;
    protected $side;

    public function setUp()
    {
        $this->entity = $this->getEntity();
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Entity::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Property::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    /**
     * @covers ::entity
     * @covers ::<protected>
     */
    public function testEntity()
    {
        $this->assertEquals($this->entity, $this->property->entity());
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
        $value = $this->getValue();
        
        $this->assertLoaded(false);
        $this->prepareLoad($value);
        $this->assertEquals($value, $property->value());
        $this->assertEquals($value, $property());
        $this->assertLoaded(true, $value);
        $property->reset();

        $this->assertLoaded(false);
        $this->prepareLoad($value);
        $this->assertEquals($value, $property());
        $this->assertLoaded(true, $value);
        $property->reset();

        $this->assertLoaded(false);
        $this->property->setValue($value);
        $this->assertLoaded(true, $value);
        $this->assertEquals($value, $property());

        $this->prepareLoad($value);
        $this->assertEquals($value, $property->reload());
        $this->assertLoaded(true, $value);
        $property->reset();
    }

    protected function assertLoaded($isLoaded, $value = null)
    {
        $this->assertEquals($isLoaded, $this->property->isLoaded());
        if($isLoaded)
            $this->assertEquals($value, $this->property->value());
    }
    
    protected function setValueCallback($value)
    {
        $property = $this->property;
        return function() use($property, $value) {
            $property->setValue($value);
            return $value;
        };
    }
    
    abstract protected function getValue();
    abstract protected function prepareLoad($value);
    abstract protected function getEntity();

}
