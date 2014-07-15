<?php

namespace PHPixieTests\ORM\Relationships;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Map
 */
class MapTest extends \PHPixieTests\AbstractORMTest
{
    protected $map;
    protected $ormBuilder;
    protected $config;

    protected $configData = array(
        array(
            'type' => 'oneToOne'
        ),
        array(
            'type' => 'manyToMany'
        )
    );

    protected $slices = array();
    protected $relationships = array();
    protected $sides = array();


    public function setUp()
    {
        $this->ormBuilder = $this->quickMock('\PHPixie\ORM\Builder');

        $this->slices = array(
            $this->quickMock('\PHPixie\Config\Slice'),
            $this->quickMock('\PHPixie\Config\Slice')
        );
        $this->config = $this->quickMock('\PHPixie\Config\Slice', array('slice', 'data'));
        $this->method($this->config, 'data', $this->configData, array(), 0);
        $this->method($this->config, 'slice', $this->slices[0], array(0), 1);
        $this->method($this->config, 'slice', $this->slices[1], array(1), 2);

        $this->relationships = array(
            $this->abstractMock('\PHPixie\ORM\Relationships\Relationship'),
            $this->abstractMock('\PHPixie\ORM\Relationships\Relationship')
        );

        $this->method($this->ormBuilder, 'relationship', $this->relationships[0], array('oneToOne'), 0);
        $this->method($this->ormBuilder, 'relationship', $this->relationships[1], array('manyToMany'), 1);

        $this->sides = array(
            $this->getSide(),
            $this->getSide(),
            $this->getSide()
        );

        $this->method($this->relationships[0], 'getSides', array($this->sides[0], $this->sides[1]), array($this->slices[0]), 0);
        $this->method($this->relationships[1], 'getSides', array($this->sides[2]), array($this->slices[1]), 0);

        foreach($this->sides as $key => $side) {
            $this->method($side, 'modelName', $key !== 1 ? 'fairy' : 'flower', array());
            $this->method($side, 'propertyName', 'prop'.$key, array());
        }

        $this->map = new \PHPixie\ORM\Relationships\Map($this->ormBuilder, $this->config);
    }

    /**
     * @covers ::__construct
     * @covers ::addSidesFromConfig
     * @covers ::addSide
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        $this->assertEquals($this->sides[0], $this->map->getSide('fairy', 'prop0'));
        $this->assertEquals($this->sides[1], $this->map->getSide('flower', 'prop1'));
        $this->assertEquals($this->sides[2], $this->map->getSide('fairy', 'prop2'));
    }

    public function testAddSideSamePropertyException()
    {
        $side = $this->getSide();
        $this->method($side, 'modelName', 'fairy', array());
        $this->method($side, 'propertyName', 'prop0', array());
        $this->setExpectedException('\PHPixie\ORM\Exception\Mapper');
        $this->map->addSide($side);
    }

    /**
     * @covers ::getSide
     * @covers ::<protected>
     */
    public function testGetSide()
    {
        $this->assertEquals($this->sides[1], $this->map->getSide('flower', 'prop1'));
        $this->setExpectedException('\Exception');
        $this->map->getSide('fairy', 'prop1');
    }

    /**
     * @covers ::modelProperty
     * @covers ::<protected>
     */
    public function testModelProperty()
    {
        $this->propertyTest('modelProperty');
    }

    /**
     * @covers ::queryProperty
     * @covers ::<protected>
     */
    public function testQueryProperty()
    {
        $this->propertyTest('queryProperty');
    }

    protected function propertyTest($method)
    {
        $property = $this->getProperty();

        $model = $this->getModel();
        $this->method($model, 'modelName', 'fairy', array(), 0);
        $this->method($this->sides[0], 'relationshipType', 'oneToOne', array(), 0);
        $this->method($this->ormBuilder, 'relationship', $this->relationships[0], array('oneToOne'), 0);
        $this->method($this->relationships[0], $method, $property, array($this->sides[0], $model), 0);
        $this->assertEquals($property, $this->map->$method($model, 'prop0'));
    }

    protected function getProperty()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property');
    }

    protected function getModel()
    {
        return $this->abstractMock('\PHPixie\ORM\Model');

    }

    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side');
    }


}
