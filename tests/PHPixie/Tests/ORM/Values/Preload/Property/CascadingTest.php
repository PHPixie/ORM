<?php

namespace PHPixie\Tests\ORM\Values\Preload\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Values\Preload\Property\Cascading
 */
class CacadingTest extends \PHPixie\Tests\ORM\Values\Preload\PropertyTest
{
    protected $preload;
    
    public function setUp()
    {
        $this->preload = $this->quickMock('\PHPixie\ORM\Values\Preload');
        parent::setUp();
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
        return new \PHPixie\ORM\Values\Preload\Property\Cascading($this->propertyName, $this->preload);
    }
}