<?php

namespace PHPixieTests\ORM\Planners\Planner\Pivot;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot\Pivot
 */
class PivotTest extends \PHPixieTests\AbstractORMTest
{
    protected $connection;
    protected $pivot;
    
    public function setUp()
    {
        $this->connection = $this->quickMock('\PHPixie\Database\Connection');
        $this->pivot = new \PHPixie\ORM\Planners\Planner\Pivot\Pivot($this->connection, 'pixie');
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
}