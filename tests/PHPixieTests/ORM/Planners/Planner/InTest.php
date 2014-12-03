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
        $pdoConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $this->collectionTest($pdoConnection, $pdoConnection, 'subquery');
        
        $otherPdoConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $this->collectionTest($pdoConnection, $otherPdoConnection, 'multiquery');
        
        $mongoConnection = $this->quickMock('\PHPixie\Database\Driver\Mongo\Connection');
        $this->collectionTest($mongoConnection, $mongoConnection, 'multiquery');

    }
    
    /**
     * @covers ::<protected>
     * @covers ::result
     */
    public function testResult()
    {
		$query = $this->itemsQueryMock(array('getWhereBuilder'));
		$resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
		$plan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
		
		$this->prepareResultTest($query, $resultStep, $plan, 'or', true);
        $this->planner->result($query, 'pixie', $resultStep, 'fairy', $plan, 'or', true);
        $this->prepareResultTest($query, $resultStep, $plan, 'and', false);
        $this->planner->result($query, 'pixie', $resultStep, 'fairy', $plan);
    }
    
    /**
     * @covers ::<protected>
     * @covers ::subquery
     */
    public function testSubquery()
    {
		$pdoConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $this->subqueryTest($pdoConnection, $pdoConnection, 'subquery');
        
        $otherPDOConnection = $this->quickMock('\PHPixie\Database\Driver\PDO\Connection');
        $this->subqueryTest($pdoConnection, $otherPDOConnection, 'multiquery');
        
        $mongoConnection = $this->quickMock('\PHPixie\Database\Driver\Mongo\Connection');
        $this->subqueryTest($mongoConnection, $mongoConnection, 'multiquery');
    }

    protected function subqueryTest($queryConnection, $subqueryConnection, $strategy)
    {
        foreach(array(
            array(array(), array('and', false)),
            array(array('or', true), array('or', true))
        ) as $params){
            $query = $this->itemsQueryMock(array('connection'));
            $subquery = $this->itemsQueryMock(array('connection'));
            $plan = $this->quickMock('\PHPixie\ORM\Plans\Plan');
            $this->method($query, 'connection', $queryConnection);
            $this->method($subquery, 'connection', $subqueryConnection);
            $this->method($this->strategies, 'in', $this->$strategy, array($strategy), 0);
            $this->method($this->$strategy, 'in', null, array_merge(array($query, 'pixie', $subquery, 'fairy', $plan), $params[1]), 0);
            call_user_func_array(array($this->planner, 'subquery'), array_merge(array($query, 'pixie', $subquery, 'fairy', $plan), $params[0]));
        }
	}
    
    public function subquery($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $strategy = $this->selectStrategy($dbQuery->connection(), $subqery->connection());
        $strategy->in($query, $queryField, $subquery, $subqueryField, $plan, $logic, $negate);
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
        foreach(array(
            array(array(), array('and', false)),
            array(array('or', true), array('or', true))
        ) as $params){
            $query = $this->itemsQueryMock(array('startWhereGroup', 'endWhereGroup', 'where', 'connection'));
            $collection = $this->quickMock('\PHPixie\ORM\Planners\Collection');
            $plan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
            $collectionQueries = array(
                $this->quickMock('\PHPixie\ORM\Model', array('planFind')),
                $this->quickMock('\PHPixie\ORM\Model', array('planFind')),
            );

            $this->method($this->strategies, 'in', $this->$strategy, array($strategy), 0);

            $this->method($query, 'startWhereGroup', null, $params[1], 0);
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
                $subquery = $this->itemsQueryMock();
                $resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
                $this->method($resultStep, 'query', $subquery, array(), 0);

                $this->method($findPlan, 'resultStep', $resultStep, array(), 1);
                $this->method($this->$strategy, 'in', null, array($query, 'pixie', $subquery, 'fairy', $plan, 'or', false), $key);

            }

            $this->method($query, 'endWhereGroup', null, array(), 3);
            call_user_func_array(array($this->planner, 'collection'), array_merge(array($query, 'pixie', $collection, 'fairy', $plan), $params[0]));
        }
    }

    protected function itemsQueryMock($methods = array())
    {
        return $this->abstractMock('\PHPixie\Database\Query\Items', $methods);
    }
    
    protected function getPlanner()
    {
        return new \PHPixie\ORM\Planners\Planner\In($this->strategies, $this->steps);
    }
}
