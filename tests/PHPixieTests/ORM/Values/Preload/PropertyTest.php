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
        $this->preload = $this->quickMock('\PHPixie\ORM\Values\Preload');
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
    
    /**
     * @covers ::preload
     * @covers ::<protected>
     */
    public function testPreload()
    {
        $this->assertEquals($this->preload, $this->property->preload());
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Values\Preload\Property($this->propertyName, $this->preload);
    }
}