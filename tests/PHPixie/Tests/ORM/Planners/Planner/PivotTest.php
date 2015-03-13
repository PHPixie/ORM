<?php

namespace PHPixie\Tests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot
 */
class PivotTest extends \PHPixie\Tests\ORM\Planners\PlannerTest
{
    protected $planners;
    protected $steps;
    protected $database;
    
    protected $plannerMocks = array();
    
    protected $sqlStategy;
    protected $multiqueryStrategy;
    
    public function setUp()
    {
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->database = $this->quickMock('\PHPixie\ORM\Database');
        
        $this->plannerMocks = array(
            'query' => $this->quickMock('\PHPixie\ORM\Planners\Planner\Query'),
            'in'    => $this->quickMock('\PHPixie\ORM\Planners\Planner\In')
        );
        
        foreach($this->plannerMocks as $name => $planner) {
            $this->method($this->planners, $name, $planner, array());
        }
        
        $this->sqlStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL');
        $this->multiqueryStrategy  = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery');
        
        parent::setUp();
        
     }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::pivot
     * @covers ::<protected>
     */
    public function testPivot()
    {
        $connection = $this->getConnection();
        $pivot = $this->planner()->pivot($connection, 'fairies');
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
        $repository = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
        $side = $this->planner()->side($items, $repository, 'fairy');
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
        $sqlConnection = $this->getConnection(true);
        $otherSqlConnection = $this->getConnection(true);
        $connection = $this->getConnection();
        
        foreach(array(
            array(array($sqlConnection, $sqlConnection, $sqlConnection), 'sql'),
            array(array($sqlConnection, $otherSqlConnection, $sqlConnection), 'multiquery'),
            array(array($connection, $connection, $connection), 'multiquery'),
            array(array($sqlConnection, $sqlConnection, null), 'sql'),
        ) as $params){
            
            $plan = $this->getStepsPlan();
            $pivot = $this->getPivot();
            $firstSide = $this->getSide();
            
            $this->method($pivot, 'connection', $params[0][0], array(), 0);
            $this->method($firstSide, 'connection', $params[0][1], array());
            
            $secondSide = null;
            if($params[0][2] !== null) {
                $secondSide = $this->getSide();
                $this->method($secondSide, 'connection', $params[0][2], array());
            }
            
            $strategy = $params[1].'Strategy';
            
            $this->method($this->$strategy, 'link', null, array($pivot, $firstSide, $secondSide, $plan), 0);
            $this->plannerMock()->link($pivot, $firstSide, $secondSide, $plan);
        }
    }
    
    /**
     * @covers ::unlink
     * @covers ::<protected>
     */
    public function testUnlink()
    {
        $pivot = $this->getPivot();
        $firstSide = $this->getSide();
        $plan = $this->getStepsPlan();
        $secondSide = $this->getSide();
        
        $this->prepareUnlinkSides($pivot, $firstSide, $plan, $secondSide);
        $this->planner()->unlink($pivot, $firstSide, $secondSide, $plan);
    }
    
    /**
     * @covers ::unlinkAll
     * @covers ::<protected>
     */
    public function testUnlinkAll()
    {
        $pivot = $this->getPivot();
        $side = $this->getSide();
        $plan = $this->getStepsPlan();
        
        $this->prepareUnlinkSides($pivot, $side, $plan);
        $this->planner()->unlinkAll($pivot, $side, $plan);
    }
    
    /**
     * @covers ::pivotByConnectionName
     * @covers ::<protected>
     */
    public function testPivotByConnectionName()
    {
        $pivot = $this->getPivot();
        $connection = $this->getConnection();
        
        $this->method($this->database, 'connection', $connection, array('pixie'), 0);
        
        $pivotMock = $this->plannerMock();
        $this->method($pivotMock, 'pivot', $pivot, array($connection, 'test'), 0);
        
        $pivotMock->pivotByConnectionName('pixie', 'test');
    }
    
    /**
     * @covers ::buildSqlStrategy
     * @covers ::<protected>
     */
    public function testBuildSqlStrategy()
    {
        $this->assertStrategy('sql', '\PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL', array(
            'planners' => $this->planners,
            'steps'    => $this->steps
        ));
    }
    
    /**
     * @covers ::buildMultiqueryStrategy
     * @covers ::<protected>
     */
    public function testBuildMutiqueryStrategy()
    {
        $this->assertStrategy('multiquery', '\PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery', array(
            'planners' => $this->planners,
            'steps'    => $this->steps
        ));
    }
    
    protected function prepareUnlinkSides($pivot, $firstSide, $plan, $secondSide = null)
    {
        $sides = array($firstSide);
        
        if($secondSide !== null) {
            $sides[]= $secondSide;
        }
        
        $deleteQuery = $this->getDatabaseQuery('delete');
        $this->method($pivot, 'databaseDeleteQuery', $deleteQuery, array(), 0);
        
        foreach($sides as $key => $side) {
            $repository = $this->getRepository();
            $items = array($this->getDatabaseModelQuery());
            
            $this->method($side, 'pivotKey', "pivot_$key", array(), 0);
            $this->method($side, 'repository', $repository, array(), 1);
            $this->method($side, 'items', $items, array(), 2);
            
            
            $this->method($this->plannerMocks['in'], 'itemIds', null, array(
                $deleteQuery,
                "pivot_$key",
                $repository,
                $items,
                $plan
            ), $key);
        }
        
        $deleteStep = $this->getQueryStep();
        $this->method($this->steps, 'query', $deleteStep, array($deleteQuery), 0);
        
        $this->method($plan, 'add', null, array($deleteStep), 0);
    }
    
    protected function getConnection($isSQL = false)
    {
        if($isSQL) {
            return $this->abstractMock('\PHPixie\Database\Type\SQL\Connection');
        }
        
        return $this->abstractMock('\PHPixie\Database\Connection');
    }
    
    protected function getStepsPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    protected function getPivot()
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Pivot');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Side');
    }
    
    protected function plannerMock()
    {
        $mock = $this->getMock('\PHPixie\ORM\Planners\Planner\Pivot', array(
            'buildSqlStrategy',
            'buildMultiqueryStrategy',
            'pivot',
            'side'
        ), array(
            $this->planners,
            $this->steps,
            $this->database
        ));
        
        $this->method($mock, 'buildSqlstrategy', $this->sqlStrategy, array());
        $this->method($mock, 'buildMultiqueryStrategy', $this->multiqueryStrategy, array());
        
        return $mock;
    }
    
    protected function getDatabaseModelQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');    
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');    
    }
    
    protected function getQueryStep()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Step\Query');    
    }
    
    protected function getDatabaseQuery($type)
    {
        return $this->abstractMock('\PHPixie\Database\Type\SQL\Query\Type\\'.ucfirst($type));    
    }
    
    protected function planner()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot(
            $this->planners,
            $this->steps,
            $this->database
        );
    }
}