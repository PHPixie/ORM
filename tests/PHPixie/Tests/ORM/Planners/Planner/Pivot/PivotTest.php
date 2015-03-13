<?php

namespace PHPixie\Tests\ORM\Planners\Planner\Pivot;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot\Pivot
 */
class PivotTest extends \PHPixie\Test\Testcase
{
    protected $queryPlanner;
    protected $connection;
    protected $pivot;
    
    public function setUp()
    {
        $this->queryPlanner = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query');
        $this->connection = $this->quickMock('\PHPixie\Database\Connection');
        $this->pivot = new \PHPixie\ORM\Planners\Planner\Pivot\Pivot(
            $this->queryPlanner,
            $this->connection,
            'pixie'
        );
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::connection
     */
    public function testConnection()
    {
        $this->assertEquals($this->connection, $this->pivot->connection());
    }
    
    /**
     * @covers ::source
     */
    public function testSource()
    {
        $this->assertEquals('pixie', $this->pivot->source());
    }
    
    /**
     * @covers ::databaseSelectQuery
     */
    public function testDatabaseSelectQuery()
    {
        $query = $this->abstractMock('\PHPixie\Database\Query\Type\Select');
        $this->method($this->connection, 'selectQuery', $query, array(), 0);
        $this->assertSame($query, $this->pivot->databaseSelectQuery());
    }
    
    /**
     * @covers ::databaseInsertQuery
     */
    public function testDatabaseInsertQuery()
    {
        $query = $this->abstractMock('\PHPixie\Database\Query\Type\Insert');
        $this->method($this->connection, 'insertQuery', $query, array(), 0);
        $this->assertSame($query, $this->pivot->databaseInsertQuery());
    }
    
    /**
     * @covers ::databaseDeleteQuery
     */
    public function testDatabaseDeleteQuery()
    {
        $query = $this->abstractMock('\PHPixie\Database\Query\Type\Delete');
        $this->method($this->connection, 'deleteQuery', $query, array(), 0);
        $this->assertSame($query, $this->pivot->databaseDeleteQuery());
    }
}