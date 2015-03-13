<?php

namespace PHPixie\Tests\ORM\Loaders\Loader\Proxy;

/**
 * @coversDefaultClass \PHPixie\ORM\Loaders\Loader\Proxy\Preloading
 */
class PreloadingTest extends \PHPixie\Tests\ORM\Loaders\Loader\ProxyTest
{
    
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
     * @covers ::addPreloader
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testPreloadProperty()
    {
        $entities = array();
        
        for($i=0; $i<2; $i++) {
            $entity = $this->getEntity();
            $this->method($this->subloader, 'getByOffset', $entity, array($i), $i);
            $entities[]=$entity;
        }
        
        for($i=0; $i<2; $i++) {
            $propertyName = 'prop'.$i;
            $preloader = $this->getPreloader();
            
            foreach($entities as $key => $entity) {
                $property = $this->getProperty();
                $this->method($entity, 'getRelationshipProperty', $property, array($propertyName), $i, true);
                $this->method($preloader, 'loadProperty', null, array($property), $key);
            }
            
            $this->loader->addPreloader($propertyName, $preloader);
        }        

        foreach($entities as $key => $entity)
        {
            $this->assertEquals($entity, $this->loader->getByOffset($key));
        }
    }
    
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Property\Entity');
    }
    
    protected function getPreloader()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Preloader');
    }
    
    protected function getLoader()
    {
        return new \PHPixie\ORM\Loaders\Loader\Proxy\Preloading($this->loaders, $this->subloader);
    }
}