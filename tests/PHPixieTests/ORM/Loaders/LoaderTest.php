<?php

namespace PHPixieTests\ORM\Loaders;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader
 */
abstract class LoaderTest extends \PHPixieTests\AbstractORMTest
{
    protected $loaders;
    protected $loader;
    
    public function setUp()
    {
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->loader = $this->getLoader();
    }
    
    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Loaders\Loader::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testException()
    {
        $this->setExpectedException('\Exception');
        $this->loader->getByOffset(99);
    }
    
    abstract protected function getLoader();
}