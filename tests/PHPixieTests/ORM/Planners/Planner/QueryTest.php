<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Query
 */
class QueryTest extends \PHPixieTests\ORM\Planners\PlannerTest
{

    protected $sqlStrategy;
    protected $mongoStrategy;
    
    public function setUp()
    {
        $this->sqlStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query\Strategy\PDO');
        $this->mongoStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query\Strategy\Mongo');
        parent::setUp();
    }
    
    
    /**
     * @covers ::<protected>
     * @covers ::setSource
     */
    public function testSetSource()
    {
        $this->prepareStrategies();
        
        $query = $this->abstractMock('\PHPixie\Database\SQL\Query', array('table'));
        $this->method($this->sqlStrategy, 'setSource', null, array($query, 'pixie'), 0);
        $this->assertEquals($query, $this->planner->setSource($query, 'pixie'));
        
        $query = $this->abstractMock('\PHPixie\Database\Driver\Mongo\Query', array('collection'));
        $this->method($this->mongoStrategy, 'setSource', null, array($query, 'pixie'), 0);
        $this->assertEquals($query, $this->planner->setSource($query, 'pixie'));
        
    }
    
    /**
     * @covers ::<protected>
     * @covers ::setBatchData
     */
    public function testSetBatchData()
    {
        $this->prepareStrategies();
        
        $query = $this->abstractMock('\PHPixie\Database\SQL\Query', array('table'));
        $this->method($this->sqlStrategy, 'setBatchData', null, array($query, array(1), array(2)), 0);
        $this->assertEquals($query, $this->planner->setBatchData($query, array(1), array(2)));
        
        $query = $this->abstractMock('\PHPixie\Database\Driver\Mongo\Query', array('collection'));
        $this->method($this->mongoStrategy, 'setBatchData', null, array($query, array(1), array(2)), 0);
        $this->assertEquals($query, $this->planner->setBatchData($query, array(1), array(2)));
        
    }
    
    protected function prepareStrategies()
    {
        $this->method($this->strategies, 'query', $this->sqlStrategy, array('SQL'), 0);
        $this->method($this->strategies, 'query', $this->mongoStrategy, array('Mongo'), 1);
    }
    
    protected function getPlanner()
    {
        return new \PHPixie\ORM\Planners\Planner\Query($this->strategies);
    }
}