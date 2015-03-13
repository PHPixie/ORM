<?php

namespace PHPixie\Tests\ORM\Maps\Map\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Property\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Maps\Map\PropertyTest
{
    
    /**
     * @covers ::property
     * @covers ::<protected>
     */
    public function testProperty()
    {
        $side = $this->side('fairy', 'trees');
        $query = $this->getQuery();
        $relationship = $this->prepareRelationship('oneToOne');
        $property = $this->getProperty();
        
        $this->map->add($side);
        
        $this->method($query, 'modelName', 'fairy', array(), 0);
        $this->method($side, 'relationshipType', 'oneToOne', array(), 0);
        $this->method($relationship, 'queryProperty', $property, array($side, $query), 0, true);

        $this->assertEquals($property, $this->map->property($query, 'trees'));
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function getProperty()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Property\Query');
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Type\Database');
    }
    
    protected function getSide()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Side\Property\Query');
    }
    
    protected function map()
    {
        return new \PHPixie\ORM\Maps\Map\Property\Query($this->relationships);
    }
}