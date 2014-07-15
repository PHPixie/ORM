<?php

namespace PHPixieTests\ORM\Relationships\Types\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Types\ManyToMany\Side
 */
class SideTest extends \PHPixieTests\ORM\Relationships\Relationship\SideTest
{
    protected $type = 'left';
    protected $relationshipType = 'manyToMany';
    protected $rightSide;
    
    public function setUp()
    {
        parent::setUp();
        $this->sides = array(
            'left' => $this->getSide('left'),
            'right' => $this->getSide('right')
        );
    }
    
    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertSidesMethod('modelName', array(
            'left'  => 'fairy',
            'right' => 'flower'
        ));
    }
    
    /**
     * @covers ::propertyName
     * @covers ::<protected>
     */
    public function testPropertyName()
    {
        $this->assertSidesMethod('propertyName', array(
            'left'  => 'flowers',
            'right' => 'fairies'
        ));
    }
    
    protected function getConfig()
    {
        $config = $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Side');
        
        $config->leftModel = 'fairy';
        $config->rightModel = 'flower';
        
        $config->leftProperty = 'flowers';
        $config->rightProperty = 'fairies';
        
        return $config;
    }
    
    protected function getSide($type)
    {
        return new \PHPixie\ORM\Relationships\Types\ManyToMany\Side($type, $this->config);
    }
}