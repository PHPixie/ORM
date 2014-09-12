<?php

namespace PHPixieTests\ORM\Loaders\Loader\Proxy;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Proxy\Preloading
 */
class PreloadingTest extends \PHPixieTests\ORM\Loaders\Loader\ProxyTest
{
    protected $preloaders;
    protected $preloadableModels;
    protected $properties;
    
    public function setUp()
    {
        $this->preloaders = array(
            'pixie' => $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Preloader'),
            'fairy' => $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Preloader'),
        );
        
        $this->preloadableModels = array(
            $this->quickMock('\PHPixie\ORM\Model'),
            $this->quickMock('\PHPixie\ORM\Model'),
        );
        
        $this->properties = array();
        foreach(range(0, 4) as $i) {
            $this->properties[]=$this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader');
        }
        
        parent::setUp();
    }
    
    /**
     * @covers ::addPreloader
     * @covers ::getPreloader
     */
    public function testAddGetPreloader()
    {
        foreach($this->preloaders as $relationship => $preloader){
            $this->assertEquals(null, $this->loader->getPreloader($relationship));
            $this->loader->addPreloader($relationship, $preloader);
            $this->assertEquals($preloader, $this->loader->getPreloader($relationship));
        }
    }
    
    /**
     * @covers ::<protected>
     * @covers ::offsetExists
     */
    public function testOffsetExists()
    {
        $this->method($this->subloader, 'offsetExists', true, array(0), 0);
        $this->method($this->subloader, 'offsetExists', false, array(1), 1);
        
        $this->assertEquals(true, $this->loader->offsetExists(0));
        $this->assertEquals(false, $this->loader->offsetExists(1));
    }
    
    /**
     * @covers ::<protected>
     * @covers ::getByOffset
     */
    public function testPreloadProperty()
    {
        $this->method($this->subloader, 'getByOffset', $this->preloadableModels[0], array(0), 0);
        $this->method($this->subloader, 'getByOffset', $this->preloadableModels[1], array(1), 1);
        
        $pkey = 0;
        foreach($this->preloaders as $relationship => $preloader) {
            
            foreach($this->preloadableModels as $mkey => $model) {
                $property = $this->properties[$pkey*2+$mkey];
                $this->method($preloader, 'loadFor', $property, array($model), $mkey);
                $this->method($model, 'setRelationshipProperty', null, array($relationship, $property), $pkey);
            }
            
            $this->loader->addPreloader($relationship, $preloader);
            $pkey++;
        }        

        foreach($this->preloadableModels as $key => $model)
        {
            $this->assertEquals($model, $this->loader->getByOffset($key));
        }
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Proxy\Preloading($this->loaders, $this->subloader);
    }
}