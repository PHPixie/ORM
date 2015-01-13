<?php

namespace PHPixieTests\ORM\Values\Preload;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Preload\Property
 */
class PropertyTest extends \PHPixieTests\AbstractORMTest
{
    protected $propertyName = 'fairy';
    protected $preload;
    
    protected $property;
    
    public function setUp()
    {
        $this->property = $this->property();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    
    /**
     * @covers ::propertyName
     * @covers ::<protected>
     */
    public function testPropertyName()
    {
        $this->assertEquals($this->propertyName, $this->property->propertyName());
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Values\Preload\Property($this->propertyName);
    }
}