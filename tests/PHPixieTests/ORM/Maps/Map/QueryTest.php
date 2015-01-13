<?php

namespace PHPixieTests\ORM\Sides\Map;

/**
 * @coversDefaultClass \PHPixie\ORM\Maps\Map\Query
 */
class QueryTest extends \PHPixieTests\ORM\Maps\MapTest
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
    
    protected function map()
    {
        return new \PHPixie\ORM\Maps\Map\Query($this->relationships);
    }
}