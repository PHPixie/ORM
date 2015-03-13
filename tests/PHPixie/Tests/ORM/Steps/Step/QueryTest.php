<?php

namespace PHPixie\Tests\ORM\Steps\Step;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Steps\StepTest
{
    protected $query;
    
    public function setUp()
    {
        $this->query = $this->query();
        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Steps\Step\Query::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $this->assertEquals($this->query, $this->step->query());
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $this->method($this->query, 'execute', null, array(), 0);
        $this->step->execute();
    }
    
    /**
     * @covers ::usedConnections
     */
    public function testUsedConnections()
    {
        $this->method($this->query, 'connection', $this->connections[0], array(), 0);
        $this->assertEquals(array($this->connections[0]), $this->step->usedConnections());
    }
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Query($this->query);
    }
    
    protected function query()
    {
        return $this->abstractMock('\PHPixie\Database\Query');
    }
    
}