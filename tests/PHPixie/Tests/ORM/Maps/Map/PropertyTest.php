<?php

namespace PHPixie\Tests\ORM\Maps\Map;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Property
 */
abstract class PropertyTest extends \PHPixie\Tests\ORM\Maps\MapTest
{    
    protected $relationships;
    
    public function setUp()
    {
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::getPropertyNames
     * @covers ::<protected>
     */
    public function testGetPropertyNames()
    {
        $sides = array(
            $this->side('fairy', 'flowers'),
            $this->side('fairy', 'trees'),
            $this->side('pixie', 'trees')
        );
        
        foreach($sides as $side) {
            $this->map->add($side);
        }
        
        $this->assertSame(array('flowers', 'trees'), $this->map->getPropertyNames('fairy'));
        $this->assertSame(array('trees'), $this->map->getPropertyNames('pixie'));
    }
    
    protected function prepareRelationship($type, $relationshipsAt = 0)
    {
        $relationship = $this->getRelationship();
        $this->method($this->relationships, 'get', $relationship, array($type), $relationshipsAt);
        return $relationship;
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }
}