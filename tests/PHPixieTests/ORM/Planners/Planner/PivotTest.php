<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot
 */
class PivotTest extends \PHPixieTests\ORM\Planners\PlannerTest
{
    protected $planners;
    protected $steps;
    
    protected $sqlStategy;
    protected $multiqueryStrategy;
    
    public function setUp()
    {
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        
        $this->sqlStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Strategy\SQL');
        $this->multiqueryStrategy  = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Strategy\Multiquery');
        
        parent::setUp();
        
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
        $this->modifyLinkTest('link');
    }
    
    /**
     * @covers ::unlink
     * @covers ::<protected>
     */
    public function testUnlink()
    {
        $this->modifyLinkTest('unlink');
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
    
    protected function modifyLinkTest($type)
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
            
            $this->method($this->$strategy, $type, null, array($pivot, $firstSide, $secondSide, $plan), 0);
            $this->plannerMock()->$type($pivot, $firstSide, $secondSide, $plan);
        }
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
        $mock = $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot', array(
            'buildSqlStrategy',
            'buildMultiqueryStrategy'
        ), array(
            $this->planners,
            $this->steps
        ));
        
        $this->method($mock, 'buildSqlstrategy', $this->sqlStrategy, array());
        $this->method($mock, 'buildMultiqueryStrategy', $this->multiqueryStrategy, array());
        
        return $mock;
    }
    
    protected function planner()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot(
            $this->planners,
            $this->steps
        );
    }
}