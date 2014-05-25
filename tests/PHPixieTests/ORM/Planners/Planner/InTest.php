<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\In
 */
class InTest extends \PHPixieTests\ORM\Planners\PlannerTest
{

    protected $multiquery;
    protected $subquery;
    
    public function setUp()
    {
        $this->multiquery = $this->quickMock('\PHPixie\ORM\Planners\Planner\In\Strategy\Multiquery');
        $this->subquery = $this->quickMock('\PHPixie\ORM\Planners\Planner\In\Strategy\Subquery');
        parent::setUp();
    }
    
    
    /**
     * @covers ::<protected>
     * @covers ::collection
     */
    public function testCollectionSubquery()
    {
        $this->method($this->strategies, 'in', $this->subquery, array('subquery'), 0);
        $collectionConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $queryConnection = $collectionConnection;
        $this->collectionTest($collectionConnection, $queryConnection, $this->subquery);
    }
    
    /**
     * @covers ::<protected>
     * @covers ::collection
     */
    public function testCollectionNonPDOMultiquery()
    {
        $this->method($this->strategies, 'in', $this->subquery, array('multiquery'), 0);
        $collectionConnection = $this->quickMock('\PHPixie\Database\Driver\Mongo\Connection');
        $queryConnection = $collectionConnection;
        $this->collectionTest($collectionConnection, $queryConnection, $this->subquery);
    }
    
    /**
     * @covers ::<protected>
     * @covers ::collection
     */
    public function testCollectionDifferentMultiquery()
    {
        $this->method($this->strategies, 'in', $this->subquery, array('multiquery'), 0);
        $collectionConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $queryConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $this->collectionTest($collectionConnection, $queryConnection, $this->subquery);
    }
    
    public function testResult()
    {
		$query = $this->quickMock('\PHPixie\Database\Query\Items', array('getWhereBuilder'));
		$resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
		$plan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
		
		$this->prepareResultTest($query, $resultStep, $plan, 'or', true);
        $this->planner->result($query, 'pixie', $resultStep, 'fairy', $plan, 'or', true);
        $this->prepareResultTest($query, $resultStep, $plan, 'and', false);
        $this->planner->result($query, 'pixie', $resultStep, 'fairy', $plan);
    }
    
    public function testSubquerySamePdo()
    {
		$connection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
	}
    
    protected function prepareSubqueryTest($query, $subquery, $strategy, $logic, $negate)
    {
		$query = $this->quickMock('\PHPixie\Database\Query\Items', array('connection'));
	}
    
    protected function prepareResultTest($query, $resultStep, $plan, $logic, $negate)
    {
        $placeholder = $this->quickMock('\PHPixie\Database\Conditions\Condition\Placeholder');
        $builder = $this->quickMock('\PHPixie\Database\Conditions\Builder', array('addPlaceholder'));
        
        $this->method($query, 'getWhereBuilder', $builder, array(), 0);
        $this->method($builder, 'addPlaceholder', $placeholder, array($logic, $negate), 0);
        
        $inStep = $this->quickMock('\PHPixie\ORM\Steps\Step\In');
        $this->method($this->steps, 'in', $inStep, array($placeholder, 'pixie', $resultStep, 'fairy'), 0);
        
        $this->method($plan, 'add', null, array($inStep), 0);
	}
	
    protected function collectionTest($collectionConnection, $queryConnection, $strategy)
    {
        $query = $this->quickMock('\PHPixie\Database\Query\Items', array('startWhereGroup', 'endWhereGroup', 'where', 'connection'));
        $collection = $this->quickMock('\PHPixie\ORM\Planners\Collection');
        $plan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
        $collectionQueries = array(
            $this->quickMock('\PHPixie\Database\SQL\Query\Items', array('planFind')),
            $this->quickMock('\PHPixie\Database\SQL\Query\Items', array('planFind')),
        );
        
        
        $this->method($query, 'startWhereGroup', null, array('or', true), 0);
        $this->method($collection, 'modelField', array(1,2,3), array('fairy'), 0);
        $this->method($query, 'where', null, array('pixie', 'in', array(1,2,3)), 1);
        $this->method($query, 'connection', $queryConnection, array(), 2);
        $this->method($collection, 'queries', $collectionQueries, array(), 1);
        $this->method($collection, 'connection', $collectionConnection, array(), 2);
        
        foreach($collectionQueries as $key => $collectionQuery) {
            $findPlan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Composite\Loader');
            $requiredPlan = $this->quickMock('\PHPixie\ORM\Plans\Plan');
            $this->method($findPlan, 'requiredPlan', $requiredPlan, array(), 0);
            
            $this->method($collectionQuery, 'planFind', $findPlan, array(), 0);
            $this->method($plan, 'appendPlan', null, array($requiredPlan), $key);
            $subquery = $this->quickMock('\PHPixie\Database\SQL\Query\Items');
            $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
            $this->method($resultStep, 'query', $subquery, array(), 0);
            
            $this->method($findPlan, 'resultStep', $resultStep, array(), 1);
            $this->method($strategy, 'in', null, array($query, 'pixie', $subquery, 'fairy', $plan, 'or', false), $key);
        }
        
        $this->method($query, 'endWhereGroup', null, array(), 3);
        $this->planner->collection($query, 'pixie', $collection, 'fairy', $plan, 'or', true);
    }

    protected function prepareStrategies()
    {
        /*
        $this->method($this->strategies, 'query', $this->pdoStrategy, 0, true, array('PDO'));
        $this->method($this->strategies, 'query', $this->mongoStrategy, 1, true, array('Mongo'));
        */
    }
    
    protected function getPlanner()
    {
        return new \PHPixie\ORM\Planners\Planner\In($this->strategies, $this->steps);
    }
}
