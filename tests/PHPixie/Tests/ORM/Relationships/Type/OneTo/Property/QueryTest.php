<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Property\Query
 */
abstract class QueryTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Property\QueryTest
{
    protected $config;
    
    public function setUp()
    {
        $this->config = $this->config();
        parent::setUp();
    }
    
    protected function prepareQuery()
    {
        $query = $this->getQuery();
        $this->method($this->handler, 'query', $query, array($this->side, $this->query), 0);
        return $query;
    }
    
    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
}