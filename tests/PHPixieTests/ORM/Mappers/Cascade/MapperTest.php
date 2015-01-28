<?php

namespace PHPixieTests\ORM\Mappers\Cascade;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Cascade\Mapper
 */
abstract class MapperTest extends \PHPixieTests\AbstractORMTest
{
    protected $mappers;
    protected $relationships;
    protected $maps;
    
    protected $cascadeMapper;
    
    protected $entityMap;
    protected $modelName = 'fairy';
    
    public function setUp()
    {
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->maps = $this->quickMock('\PHPixie\ORM\Maps');
        
        $this->cascadeMapper = $this->cascadeMapper();
        
        $this->entityMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Entity');
        $this->method($this->maps, 'entity', $this->entityMap, array());
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::isModelHandled
     * @covers ::<protected>
     */
    public function testIsModelHandled()
    {
        $this->prepareGetHandledSides(array());
        $this->assertEquals(false, $this->cascadeMapper->isModelHandled($this->modelName));
        
        $this->prepareGetHandledSides(array('oneToOne', 'oneToMany'));
        $this->assertEquals(true, $this->cascadeMapper->isModelHandled($this->modelName));
    }
    
    protected function prepareGetHandledSides($relationshipTypes)
    {
        $handledSides = array();
        $sides = array();
        
        foreach($relationshipTypes as $type) {
            $handled = $this->getHandledSides($type);
            $notHandled = $this->getNotHandledSides();
            
            $sides = array_merge($sides, $handled, $notHandled);
            $handledSides = array_merge($handledSides, $handled);
        }
        
        $this->method($this->entityMap, 'getModelSides', $sides, array($this->modelName), 0);
        
        return $handledSides;
    }
    
    protected function getReusableResult()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Result\Reusable');
    }
    
    protected function getPath()
    {
        return $this->abstractMock('\PHPixie\ORM\Mappers\Cascade\Path');
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }
    
    protected function getPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    abstract protected function getHandler();
    abstract protected function getHandledSides($relationshipType);
    abstract protected function getNotHandledSides();
    abstract protected function setSideIsHandled($side, $isHandled);
    
    abstract protected function cascadeMapper();
}