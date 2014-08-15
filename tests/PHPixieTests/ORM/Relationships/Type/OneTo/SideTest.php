<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Side
 */
abstract class SideTest extends \PHPixieTests\ORM\Relationships\Relationship\SideTest
{
    protected $type = 'owner';
    protected $relationshipType = 'oneToMany';
    protected $itemSideName;
    protected $ownerProperty;


    public function setUp()
    {
        parent::setUp();
        $this->sides = array(
            'owner' => $this->getSide('owner'),
            $this->itemSideName => $this->getSide($this->itemSideName)
        );
    }

    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertSidesMethod('modelName', array(
            'owner'  => 'flower',
            $this->itemSideName => 'fairy'
        ));
    }

    /**
     * @covers ::propertyName
     * @covers ::<protected>
     */
    public function testPropertyName()
    {
        $this->assertSidesMethod('propertyName', array(
            'owner'  => $this->ownerProperty,
            $this->itemSideName => 'pixie'
        ));
    }

    protected function config()
    {
        $config = $this->getConfig();

        $config->ownerModel = 'fairy';
        $config->itemModel  = 'flower';

        $config->ownerProperty = $this->ownerProperty;
        $config->itemProperty  = 'pixie';

        return $config;
    }

    abstract protected function getConfig();

}
