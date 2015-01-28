<?php

namespace PHPixieTests\ORM\Mappers;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Preload
 */
class PreloadTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationships;
    protected $maps;
    
    protected $preloadMapper;
    
    protected $entityMap;
    protected $modelName = 'fairy';
    
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->maps = $this->quickMock('\PHPixie\ORM\Maps');
        
        $this->entityMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Entity');
        $this->method($this->maps, 'entity', $this->entityMap, array());
        
        $this->preloadMapper = new \PHPixie\ORM\Mappers\Preload(
            $this->relationships,
            $this->maps
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
        
        $this->prepareMapTest($preloadingProxy, $preload, $reusableResult, $stepsPlan);
        $this->preloadMapper->map(
            $preloadingProxy,
            $this->modelName,
            $preload,
            $reusableResult,
            $stepsPlan
        );
    }

    protected function prepareMapTest($preloadingProxy, $preload, $reusableResult, $stepsPlan)
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
            $this->method($this->entityMap, 'get', $side, array($this->modelName, $set[0]), $key);
            $this->method($side, 'relationshipType', $set[1], array(), 0);
            
            $relationship = $this->getRelationship();
            $this->method($this->relationships, 'get', $relationship, array($set[1]), $key);
            
            $preloader = $this->getPreloader();
            
            $handler = $this->getPreloadingHandler();
            $this->method($handler, 'mapPreload', $preloader, array(
                $side,
                $property,
                $reusableResult,
                $stepsPlan
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