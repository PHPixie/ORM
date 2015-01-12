<?php

namespace PHPixieTests\ORM\Maps;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map
 */
abstract class MapTest extends \PHPixieTests\AbstractORMTest
{
    protected $relationships;
    protected $map;
    
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->map = $this->map();
    }

    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::add
     * @covers ::get
     * @covers ::getPropertyNames
     * @covers ::<protected>
     */
    public function testAddSide()
    {
        $sides = array(
            $this->side('fairy', 'flowers'),
            $this->side('fairy', 'trees'),
            $this->side('pixie', 'trees')
        );
        
        foreach($sides as $side) {
            $this->map->add($side);
        }
        
        foreach($sides as $side) {
            $this->assertSame($side, $this->map->get($side->modelName(), $side->propertyName()));
        }
        
        $this->assertSame(array('flowers', 'trees'), $this->map->getPropertyNames('fairy'));
        $this->assertSame(array('trees'), $this->map->getPropertyNames('pixie'));
    }
    
    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAddDuplicateSide()
    {
        $side = $this->side('fairy', 'flowers');
        $this->map->add($side);
        $this->setExpectedException('\PHPixie\ORM\Exception\Relationship');
        $this->map->add($side);
    }
    
    protected function side($modelName, $propertyName)
    {
        $side = $this->getSide();
        $this->method($side, 'modelName', $modelName, array());
        $this->method($side, 'propertyName', $propertyName, array());
        
        return $side;
    }
    
    protected function prepareRelationship($type, $relationshipsAt = 0)
    {
        $relationship = $this->getRelationship();
        $this->method($this->relationships, 'get', $relationship, array($type), $relationshipsAt);
        return $relationship;
    }
    
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side');
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }
    
    abstract protected function map();
}