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
            'pixie' => $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Preloader'),
            'fairy' => $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Preloader'),
        );
        
        $this->preloadableModels = array(
            $this->quickMock('\PHPixie\ORM\Model'),
            $this->quickMock('\PHPixie\ORM\Model'),
        );
        
        $this->properties = array();
        foreach(range(0, 4) as $i) {
            $this->properties[]=$this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Preloader');
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
     * @covers ::getByOffser
     */
    public function testPreloadProperty()
    {
        $this->preparePreloadableModels();
        
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
    
    abstract protected function preparePreloadableModels();
}