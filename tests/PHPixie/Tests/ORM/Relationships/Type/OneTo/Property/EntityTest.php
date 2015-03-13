<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Property\Entity
 */
abstract class EntityTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Property\EntityTest
{
    protected $config;
    protected $loadPropertyMethod;
    
    public function setUp()
    {
        $this->config = $this->config();
        parent::setUp();
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $query = $this->getQuery();
        $this->method($this->handler, 'query', $query, array($this->side, $this->entity), 0);
        $this->assertEquals($query, $this->property->query());
    }
    
    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
}