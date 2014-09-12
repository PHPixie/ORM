<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot
 */
class PivotTest extends \PHPixieTests\ORM\Planners\PlannerTest
{
    
    protected $SQL;
    protected $multiquery;
    
    public function setUp()
    {
        $this->SQL = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL');
        $this->multiquery = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery');
        parent::setUp();
    }
    
    /**
     * @covers ::pivot
     * @covers ::<protected>
     */
    public function testPivot()
    {
        $connection = $this->quickMock('\PHPixie\Database\Connection');
        $pivot = $this->planner->pivot($connection, 'fairies');
        $this->assertInstanceOf('\PHPixie\ORM\Planners\Planner\Pivot\Pivot', $pivot);
        $this->assertEquals($connection, $pivot->connection());
        $this->assertEquals('fairies', $pivot->source());
    }
    
    /**
     * @covers ::side
     * @covers ::<protected>
     */
    public function testSide()
    {
        $items = array(5);
        $repository = $this->quickMock('\PHPixie\ORM\Repositories\Repository');
        $side = $this->planner->side($items, $repository, 'fairy');
        $this->assertInstanceOf('\PHPixie\ORM\Planners\Planner\Pivot\Side', $side);
        $this->assertEquals($items, $side->items());
        $this->assertEquals($repository, $side->repository());
        $this->assertEquals('fairy', $side->pivotKey());
    }
    
    /**
     * @covers ::link
     * @covers ::<protected>
     */
    public function testLink()
    {
        $this->linkUnlinkTest('link');
    }
    
    /**
     * @covers ::unlink
     * @covers ::<protected>
     */
    public function testUnlink()
    {
        $this->linkUnlinkTest('unlink');
    }
    
    protected function linkUnlinkTest($type)
    {
        $pdoConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $otherPdoConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $mongoConnection = $this->quickMock('\PHPixie\Database\Driver\Mongo\Connection');
        
        foreach(array(
            array(array($pdoConnection, $pdoConnection, $pdoConnection), 'SQL'),
            array(array($pdoConnection, $otherPdoConnection, $pdoConnection), 'multiquery'),
            array(array($mongoConnection, $mongoConnection, $mongoConnection), 'multiquery'),
            array(array($pdoConnection, $pdoConnection, null), 'SQL'),
        ) as $params){
            
            $plan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
            $pivot = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Pivot');
            $firstSide = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Side');
            $this->method($pivot, 'connection', $params[0][0], array(), 0);
            $this->method($firstSide, 'connection', $params[0][1], array());
            
            $secondSide = null;
            if($params[0][2] !== null) {
                $secondSide = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Side');
                $this->method($secondSide, 'connection', $params[0][2], array());
            }
            
            $strategy = $params[1];
            $this->method($this->strategies, 'pivot', $this->$strategy, array($strategy), 0);
            $this->method($this->$strategy, $type, null, array($pivot, $firstSide, $secondSide, $plan), 0);
            $this->planner->$type($pivot, $firstSide, $secondSide, $plan);
        }
    }
    
    protected function getPlanner()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot($this->strategies);
    }
}