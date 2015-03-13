<?php

namespace PHPixie\Tests\ORM\Maps;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map
 */
abstract class MapTest extends \PHPixie\Test\Testcase
{
    protected $map;
    
    public function setUp()
    {
        $this->map = $this->map();
    }
    
    /**
     * @covers ::add
     * @covers ::get
     * @covers ::getModelSides
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
        
        $this->assertSame(array(
            'flowers' => $sides[0],
            'trees'   => $sides[1]
        ), $this->map->getModelSides('fairy'));
        $this->assertSame(array(
            'trees' => $sides[2]
        ), $this->map->getModelSides('pixie'));
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
    
    abstract protected function getSide();
    abstract protected function map();
}