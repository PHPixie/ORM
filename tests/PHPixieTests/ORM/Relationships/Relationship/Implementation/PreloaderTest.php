<?php

namespace PHPixieTests\ORM\Relationships\Relationship;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Preloader
 */
abstract class PreloaderTest extends \PHPixieTests\AbstractORMTest
{
    protected $preloader;
    protected $loader;

    public function setUp()
    {
        $this->loader    = $this->loader();
        $this->preloader = $this->preloader();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Preloader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::loader
     * @covers ::<protected>
     */
    public function testLoader()
    {
        $this->assertEquals($this->loader, $this->preloader->loader());
    }
    
    protected function property($model, $expectedValue)
    {
        $property = $this->getProperty();
        $this->method($property, 'model', $model, array());
        $property
            ->expects($this->once())
            ->method('setValue')
            ->with($this->identicalTo($expectedValue));
        return $property;
    }
    
    protected function getProperty()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Model');
    }
    
    abstract protected function getModel();
    abstract protected function loader();
    abstract protected function preloader();
    
}