<?php

namespace PHPixieTests\ORM\Mappers;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Preload
 */
class PreloadTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationships;
    protected $relationshipMap;
    protected $modelName = 'fairy';
    
    protected $preloadMapper;
    
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Relationships\Map');
        $this->method($this->relationships, 'map', $this->relationshipMap, array(), 0);
        
        $this->preloadMapper = new \PHPixie\ORM\Mappers\Preload($this->relationships);
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::mapPreload
     * @covers ::<protected>
     */
    public function testMapPreload()
    {
        $preloadingProxy = $this->getPreloadingProxy();
        $preload = $this->getPreload();
        $reusableResult = $this->getReusableResult();
        $stepsPlan = $this->getStepsPlan();
        
        $this->prepareMapTest($preloadingProxy, $preload, $reusableResult, $stepsPlan);
        $this->preloadMapper->mapPreload(
            $preloadingProxy,
            $this->modelName,
            $preload,
            $reusableResult,
            $stepsPlan
        );
    }

    /**
     * @covers ::mapPreloadEmbedded
     * @covers ::<protected>
     */
    public function testMapPreloadEmbedded()
    {
        $preloadingProxy = $this->getPreloadingProxy();
        $preload = $this->getPreload();
        $reusableResult = $this->getReusableResult();
        $stepsPlan = $this->getStepsPlan();
        
        $embeddedPath = 'embedded.prefix';
        
        $this->prepareMapTest($preloadingProxy, $preload, $reusableResult, $stepsPlan, $embeddedPath);
        $this->preloadMapper->mapPreloadEmbedded(
            $preloadingProxy,
            $this->modelName,
            $preload,
            $reusableResult,
            $stepsPlan,
            $embeddedPath
        );
    }

    
    protected function prepareMapTest($preloadingProxy, $preload, $reusableResult, $stepsPlan, $embeddedPath = null)
    {
        $sets = array(
            array('pixie', 'oneToOne'),
            array('tree',  'manyToOne'),
        );
        
        $properties = array();
        
        foreach($sets as $key => $set) {
            
            $property = $this->getPreloadProperty();
            $properties[] = $property;
            
            $this->method($property, 'propertyName', $set[0], array(), 0);
            
            $side = $this->getSide();
            $this->method($this->relationshipMap, 'getSide', $side, array($this->modelName, $set[0]), $key);
            $this->method($side, 'relationshipType', $set[1], array(), 0);
            
            $relationship = $this->getRelationship();
            $this->method($this->relationships, 'get', $relationship, array($set[1]), $key);
            
            $preloader = $this->getPreloader();
            
            if($embeddedPath !== null) {
                $handler = $this->getHandler(true);
                
                $this->method($handler, 'mapPreloadEmbedded', $preloader, array(
                    $side,
                    $property,
                    $reusableResult,
                    $stepsPlan,
                    $embeddedPath
                ), 0);
                
            }else{
                $handler = $this->getHandler();
                $this->method($handler, 'mapPreload', $preloader, array(
                    $side,
                    $property,
                    $reusableResult,
                    $stepsPlan
                ), 0);
            }
            
            $this->method($relationship, 'handler', $handler, array(), 0);
            $this->method($preloadingProxy, 'addPreloader', null, array($set[0], $preloader), $key);
        }
        
        $this->method($preload, 'properties', $properties, array(), 0);
    }
    
    protected function getPreloadingProxy()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Preloading');
    }
    
    protected function getPreload()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Preload');
    }
    
    protected function getReusableResult()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Result\Reusable');
    }
    
    protected function getStepsPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    protected function getPreloadProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Preload\Property');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Side');
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }
    
    protected function getHandler($embedded = false)
    {
        if($embedded)
             return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Handler\Embedded');
        
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Handler');
    }
    
    protected function getPreloader()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Preloader');
    }
    
}