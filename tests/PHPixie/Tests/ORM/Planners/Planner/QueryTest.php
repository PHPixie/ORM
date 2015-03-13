<?php

namespace PHPixie\Tests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Planners\PlannerTest
{
    protected $sqlStrategy;
    protected $mongoStrategy;
    
    public function setUp()
    {
        $this->sqlStrategy   = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query\Strategy\SQL');
        $this->mongoStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query\Strategy\Mongo');
        
        parent::setUp();
    }
    
    /**
     * @covers ::setSource
     * @covers ::<protected>
     */
    public function testSetSource()
    {
        $this->methodTest('setSource', array('pixie'));
    }
    
    /**
     * @covers ::setBatchData    
     * @covers ::<protected>
     */
    public function testSetBatchData()
    {
        $this->methodTest('setBatchData', array(array('t'), array(1)));
    }
    
    protected function methodTest($method, $params)
    {
        $sets = array(
            array($this->sqlStrategy, $this->getSQLQuery()),
            array($this->mongoStrategy, $this->getMongoQuery())
        );
        
        $plannerMock = $this->plannerMock();
        
        array_unshift($params, null);
        
        foreach($sets as $set) {
            $params[0] = $set[1];
            
            $this->method($set[0], $method, null, $params, 0);
            $callback = array($plannerMock, $method);
            $this->assertSame($set[1], call_user_func_array($callback, $params));
        }
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Planner');
        $params[0] = $this->getQuery();
        call_user_Func_array(array($plannerMock, $method), $params);
    }
    
    /**
     * @covers ::buildSqlStrategy
     * @covers ::<protected>
     */
    public function testBuildSqlStrategy()
    {
        $this->assertStrategy('sql', '\PHPixie\ORM\Planners\Planner\Query\Strategy\SQL');
    }
    
    /**
     * @covers ::buildMongoStrategy
     * @covers ::<protected>
     */
    public function testBuildMongoStrategy()
    {
        $this->assertStrategy('mongo', '\PHPixie\ORM\Planners\Planner\Query\Strategy\Mongo');
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Query');
    }
    
    protected function getSQLQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Type\SQL\Query');
    }
    
    protected function getMongoQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Driver\Mongo\Query');
    }
    
    protected function planner()
    {
        return new \PHPixie\ORM\Planners\Planner\Query();
    }
    
    protected function plannerMock()
    {
        $mock = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query', array(
            'buildSQLStrategy',
            'buildMongoStrategy',
        ));
        
        $this->method($mock, 'buildSqlStrategy', $this->sqlStrategy, array());
        $this->method($mock, 'buildMongoStrategy', $this->mongoStrategy, array());
        
        return $mock;
    }
}