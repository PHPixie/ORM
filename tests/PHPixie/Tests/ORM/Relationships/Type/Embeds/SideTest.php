<?php

namespace PHPixie\Tests\ORM\Relationships\Type\Embeds;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\Embeds\Side
 */
abstract class SideTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\SideTest
{
    protected $relationshipType;
    protected $ownerProperty;


    public function setUp()
    {
        parent::setUp();
        $this->sides = array(
            'owner' => $this->getSide('owner')
        );
    }

    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertSidesMethod('modelName', array(
            'owner' => 'fairy'
        ));
    }
    
    /**
     * @covers ::relatedModelName
     * @covers ::<protected>
     */
    public function testRelatedModelName()
    {
        $this->assertSidesMethod('relatedModelName', array(
            'owner' => 'flower'
        ));
    }

    /**
     * @covers ::propertyName
     * @covers ::<protected>
     */
    public function testPropertyName()
    {
        $this->assertSidesMethod('propertyName', array(
            'owner' => $this->ownerProperty
        ));
    }

    protected function config()
    {
        $config = $this->getConfig();
        $config->ownerModel = 'fairy';
        $config->itemModel  = 'flower';
        
        $this->method($config, 'ownerProperty', $this->ownerProperty, array());
        return $config;
    }

    abstract protected function getConfig();

}
