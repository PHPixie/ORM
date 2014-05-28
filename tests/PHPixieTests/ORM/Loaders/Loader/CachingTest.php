<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Caching
 */
class CachingTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $subloader;
    
    public function setUp()
    {
        $this->subloader = $this->quickMock('\PHPixie\ORM\Loaders\Loader');
        parent::setUp();
    }
    
    /**
     * @covers ::getByOffset
     * @covers ::offsetExists
     */
    public function testExistsGetByOffset()
    {
        $model = $this->quickMock('\PHPixie\ORM\Model');

        $this->method($this->subloader, 'offsetExists', true, array(3), 0);
        $this->method($this->subloader, 'getByOffset', $model, array(3), 1);
        $this->method($this->subloader, 'offsetExists', false, array(4), 2);

        $this->assertEquals(true, $this->loader->offsetExists(3));
        $this->assertEquals($model, $this->loader->getByOffset(3));
        $this->assertEquals($model, $this->loader->getByOffset(3));
        $this->assertEquals(true, $this->loader->offsetExists(3));
        $this->assertEquals(false, $this->loader->offsetExists(4));
    }
    
    /**
     * @covers ::getByOffset
     */
    public function testNotFoundException(){
        $this->subloader
            ->expects($this->any())
            ->method('getByOffset')
            ->will($this->returnCallback(function(){
                throw new \PHPixie\ORM\Exception\Loader();
            }));
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Loader');
        $this->loader->getbyOffset(4);
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Caching($this->subloader);
    }
}