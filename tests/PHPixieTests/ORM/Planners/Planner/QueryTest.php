<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Query
 */
class QueryTest extends \PHPixieTests\AbstractORMTest
{
    protected $strategies;
    
    protected $queryPlanner;
    
    protected $sqlStrategy;
    protected $mongoStrategy;
    
    
    
    public function setUp()
    {
        $this->strategies = $this->quickMock('\PHPixie\ORM\Planners\Strategies');
        
        $this->queryPlanner = new \PHPixie\ORM\Planners\Planner\Query($this->strategies);
        
        $this->sqlStrategy   = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query\Strategy\SQL');
        $this->mongoStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query\Strategy\Mongo');
        
        $this->method($this->strategies, 'sqlQuery', $this->sqlStrategy, array());
        $this->method($this->strategies, 'mongoQuery', $this->mongoStrategy, array());
    }
    

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
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
        
        array_unshift($params, null);
        
        foreach($sets as $set) {
            $params[0] = $set[1];
            
            $this->method($set[0], $method, null, $params, 0);
            $callback = array($this->queryPlanner, $method);
            $this->assertSame($set[1], call_user_func_array($callback, $params));
        }
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Planner');
        $params[0] = $this->getQuery();
        call_user_Func_array(array($this->queryPlanner, $method), $params);
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
}