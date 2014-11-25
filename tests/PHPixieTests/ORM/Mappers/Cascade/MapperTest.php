<?php

namespace PHPixieTests\ORM\Mappers\Cascade;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Cascade\Mapper
 */
abstract class MapperTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationships;
    protected $relationshipMap;
    
    protected $cascadeMapper;
    
    protected $modelName = 'fairy';
    
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Relationships\Map');
        $this->method($this->relationships, 'map', $this->relationshipMap, array(), 0);
        
        $this->cascadeMapper = $this->cascadeMapper();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function __construct()
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
        foreach($relationshipTypes as $type) {
            $handledSides[]= $this->side($type, true);
        }
        
        $sides = $handledSides;
        $sides[] = $this->side('oneToOne', false);
        
        $this->method($this->relationshipMap, 'modelSides', $sides,array($this->modelName), 0);
        
        return $handledSides;
    }
    
    protected function side($relationshipType, $isHandled)
    {
        $side = $this->getSide();
        $this->method($side, 'relationshipType', $relationshipType, array());
        $this->setSideIsHandled($side, $isHandled);
        return $side;
    }
    
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side');
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
    
    protected function getHandler()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Handler');
    }
    
    protected function getPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    abstract protected function setSideIsHandled($side, $isHandled);
    abstract protected function cascadeMapper();
}