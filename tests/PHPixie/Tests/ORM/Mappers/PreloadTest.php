<?php

namespace PHPixie\Tests\ORM\Mappers;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Preload
 */
class PreloadTest extends \PHPixie\Test\Testcase
{
    protected $relationships;
    protected $preloadMap;
    
    protected $preloadMapper;
    
    protected $modelName = 'fairy';
    
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->preloadMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Property\Entity');
    
        $this->preloadMapper = new \PHPixie\ORM\Mappers\Preload(
            $this->relationships,
            $this->preloadMap
        );
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::map
     * @covers ::<protected>
     */
    public function testMapPreload()
    {
        $preloadingProxy = $this->getPreloadable();
        $preload = $this->getPreload();
        $reusableResult = $this->getReusableResult();
        $stepsPlan = $this->getStepsPlan();
        $loader = $this->getLoader();
        
        $this->prepareMapTest($preloadingProxy, $preload, $reusableResult, $stepsPlan, $loader);
        $this->preloadMapper->map(
            $preloadingProxy,
            $this->modelName,
            $preload,
            $reusableResult,
            $stepsPlan,
            $loader
        );
    }

    protected function prepareMapTest($preloadingProxy, $preload, $reusableResult, $stepsPlan, $loader)
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
            $this->method($this->preloadMap, 'get', $side, array($this->modelName, $set[0]), $key);
            $this->method($side, 'relationshipType', $set[1], array(), 0);
            
            $relationship = $this->getRelationship();
            $this->method($this->relationships, 'get', $relationship, array($set[1]), $key);
            
            $preloader = $this->getPreloader();
            
            $handler = $this->getPreloadingHandler();
            $this->method($handler, 'mapPreload', $preloader, array(
                $side,
                $property,
                $reusableResult,
                $stepsPlan,
                $loader
            ), 0);
            
            $this->method($relationship, 'handler', $handler, array(), 0);
            $this->method($preloadingProxy, 'addPreloader', null, array($set[0], $preloader), $key);
        }
        
        $this->method($preload, 'properties', $properties, array(), 0);
    }
    
    protected function getPreloadable()
    {
        return $this->abstractMock('\PHPixie\ORM\Mappers\Preload\Preloadable');
    }

    protected function getLoader()
    {
        return $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
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
    
    protected function getPreloadingHandler()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Handler\Preloading');
    }
    
    protected function getPreloader()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Preloader');
    }
    
}