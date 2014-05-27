<?php

namespace PHPixieTests\ORM\Loaders\Loader;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Preloadable
 */
abstract class PreloadableTest extends \PHPixieTests\ORM\Loaders\LoaderTest
{
    protected $preloaders;
    protected $preloadableModels;
    protected $properties;
    
    public function setUp()
    {
        $this->preloaders = array(
            $this->quickMock('\PHPixie\ORM\Loaders\Loader\Preloader'),
            $this->quickMock('\PHPixie\ORM\Loaders\Loader\Preloader'),
        );
        
        $this->preloadableModels = array(
            $this->quickMock('\PHPixie\ORM\Model'),
            $this->quickMock('\PHPixie\ORM\Model'),
        );
        
        $this->properties = array();
        foreach(range(0, 4) as $i) {
            $this->properties[]=$this->quickMock('\PHPixie\ORM\Relationships\Relationship\Property');
        }
        
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
        $this->preparePreloadableModels();
        
        foreach($this->preloaders as $pkey => $preloader) {
            foreach($this->preloadableModels as $mkey => $model) {
                $property = $this->properties[$pkey*2+$mkey];
                $this->method($preloader, 'loadFor', $property, array($model), $mkey);
                $this->method($model, 'setRelationshipProperty', null, array($property), $pkey);
            }
        }
        
        $this->loader->addPreloader('pixie', $this->preloaders[0]);
        $this->loader->addPreloader('fairy', $this->preloaders[1]);
        foreach($this->preloadableModels as $key => $model)
        {
            $this->assertEquals($model, $this->loader->getByOffset($key));
        }
    }
    
    abstract protected function preparePreloadableModels();
}