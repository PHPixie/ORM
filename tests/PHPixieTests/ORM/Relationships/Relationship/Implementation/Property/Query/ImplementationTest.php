<?php

namespace PHPixieTests\ORM\Relationships\Relationship\Property\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Property\Query\Implementation
 */
abstract class QueryTest extends \PHPixieTests\ORM\Relationships\Relationship\PropertyTest
{
    protected $query;

    public function setUp()
    {
        $this->query = $this->getQuery();
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Property\Query::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Property::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {

    }

    /**
     * @covers ::query
     * @covers ::__invoke
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $property = $this->property;
        $query = $this->prepareQuery();
        $this->assertEquals($query, $property->query());
        
        $query = $this->prepareQuery();
        $this->assertEquals($query, $property());
    }
    
    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Query');
    }
}