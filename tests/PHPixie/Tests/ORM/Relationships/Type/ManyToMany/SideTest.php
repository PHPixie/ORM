<?php

namespace PHPixie\Tests\ORM\Relationships\Type\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Side
 */
class SideTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\SideTest
{
    protected $type = 'left';
    protected $relationshipType = 'manyToMany';

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
            'left'  => 'flower',
            'right' => 'fairy'
        ));
    }
    
    /**
     * @covers ::relatedModelName
     * @covers ::<protected>
     */
    public function testRelatedModelName()
    {
        $this->assertSidesMethod('relatedModelName', array(
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
            'left'  => 'fairies',
            'right' => 'flowers'
        ));
    }

    /**
     * @covers ::isDeleteHandled
     * @covers ::<protected>
     */
    public function testIsDeleteHandled()
    {
        $this->assertSame(true, $this->side->isDeleteHandled());
    }

    
    protected function config()
    {
        $config = $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side');

        $config->leftModel = 'fairy';
        $config->rightModel = 'flower';

        $config->leftProperty = 'flowers';
        $config->rightProperty = 'fairies';

        return $config;
    }

    protected function getSide($type)
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Side($type, $this->config);
    }
}
