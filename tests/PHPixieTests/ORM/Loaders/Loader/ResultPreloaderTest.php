<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\ResultPreloader
 */
class ResultPreloaderTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $preloader;
    protected $ids = array(11, 12, 13);
    
    public function setUp()
    {
        $this->preloader = $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader');
        parent::setUp();
    }
    
    /**
     * @covers ::offsetExists
     */
    public function testOffsetExists()
    {
        foreach(range(0, 2) as $i)
            $this->assertEquals(true, $this->loader->OffsetExists($i));
        
        $this->assertEquals(false, $this->loader->OffsetExists(3));
    }
    
    /**
     * @covers ::getByOffset
     */
    public function testGetByOffset()
    {
        $model = $this->quickMock('\PHPixie\ORM\Model');
        $this->method($this->preloader, 'getModel', $model, array(12), 0);
        $this->assertEquals($model, $this->loader->getByOffset(1));
    }
    
    /**
     * @covers ::getByOffset
     */
    public function testNotFoundException(){
        $this->preloader
            ->expects($this->any())
            ->method('getModel')
            ->will($this->returnCallback(function(){
                throw new \PHPixie\ORM\Exception\Loader();
            }));
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getbyOffset(2);
    }
    
    /**
     * @covers ::getByOffset
     */
    public function testOutOfBoundsException(){
        $this->setExpectedException('\Exception');
        $this->loader->getbyOffset(4);
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\ResultPreloader($this->loaders, $this->preloader, $this->ids);
    }
}