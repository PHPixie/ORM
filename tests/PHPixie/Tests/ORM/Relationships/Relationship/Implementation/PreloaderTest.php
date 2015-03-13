<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Preloader
 */
abstract class PreloaderTest extends \PHPixie\Test\Testcase
{
    protected $preloader;

    public function setUp()
    {
        $this->preloader = $this->preloader();
    }
    
    protected function property($entity, $expectedValue)
    {
        $property = $this->getProperty();
        $this->method($property, 'entity', $entity, array());
        $property
            ->expects($this->once())
            ->method('setValue')
            ->with($this->identicalTo($expectedValue));
        return $property;
    }
    
    abstract protected function getProperty();
    abstract protected function getEntity();
    abstract protected function preloader();
    
}