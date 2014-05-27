<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Preloadable
 */
abstract class PreloadableTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $preloaders;
    protected $models;
    
    public function setUp()
    {
        $this->models
        $this->preloaders = array(
            $this->quickMock('\PHPixie\ORM\Loaders\Loader\Preloader'),
            $this->quickMock('\PHPixie\ORM\Loaders\Loader\Preloader'),
        );
        
        parent:setUp();
    }
    
    /**
     * @covers ::addPreloader
     * @covers ::getPreloader
     */
    public function testAddGetPreloader()
    {
        foreach(array(1, 2) as $key){
            $this->assertEquals(null, $this->loader->getPreloader($key));
            $this->loader->addPreloader($key, $this->preloaders[$key]);
            $this->assertEquals($this->preloaders[0], $this->loader->getPreloader($key));
        }
    }
    
    /**
     * @covers ::<protected>
     * @covers ::getByOffser
     */
    public function testPreloadProperty()
    {
        $this->prepareModels();
        
        $this->loader->addPreloader('pixie', $this->preloaders[0]);
        $this->loader->addPreloader('fairy', $this->preloaders[1]);
        
    }
    
    abstract protected function prepareModels();
}