<?php

namespace PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Query
 */
abstract class QueryTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\PropertyTest
{
    protected $query;

    public function setUp()
    {
        $this->query = $this->getQuery();
        parent::setUp();
    }

    /**
     * @covers ::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Property\Query::__construct
     * @covers \PHPixie\ORM\Relationships\Relationship\Implementation\Property::__construct
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
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
}