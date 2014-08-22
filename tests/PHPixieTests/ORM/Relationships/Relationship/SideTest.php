<?php

namespace PHPixieTests\ORM\Relationships\Relationship;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Side
 */
abstract class SideTest extends \PHPixieTests\AbstractORMTest
{
    protected $side;
    protected $type;
    protected $config;
    protected $relationshipType;
    protected $sides = array();

    public function setUp()
    {
        $this->config = $this->config();
        $this->side = $this->getSide($this->type);
    }

    /**
     * @covers ::type
     * @covers ::<protected>
     */
    public function testType()
    {
        $this->assertEquals($this->type, $this->side->type());
    }

    /**
     * @covers ::config
     * @covers ::<protected>
     */
    public function testConfig()
    {
        $this->assertEquals($this->config, $this->side->config());
    }

    /**
     * @covers ::relationshipType
     * @covers ::<protected>
     */
    public function testRelationshipType()
    {
        $this->assertEquals($this->relationshipType, $this->side->relationshipType());
    }

    protected function assertSidesMethod($method, $map)
    {
        foreach($map as $key => $value)
            $this->assertEquals($value, $this->sides[$key]->$method());
    }

    abstract public function testModelName();
    abstract public function testPropertyName();


    abstract protected function config();
    abstract protected function getSide($type);
}
