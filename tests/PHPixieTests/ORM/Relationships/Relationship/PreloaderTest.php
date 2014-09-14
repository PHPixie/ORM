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
        $this->loaders   = $this->quickMock('\PHPixie\ORM\Loaders');
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
    
    abstract protected function getModel();
    abstract protected function loader();
    abstract protected function preloader();
    
}