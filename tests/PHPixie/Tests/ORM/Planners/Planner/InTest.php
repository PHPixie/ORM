<?php

namespace PHPixie\Tests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\In
 */
class InTest extends \PHPixie\Tests\ORM\Planners\PlannerTest
{
    protected $conditions;
    protected $mappers;
    protected $steps;
    
    protected $subqueryStrategy;
    protected $multiqueryStrategy;
    
    protected $conditionsMapper;
    
    protected $queryField = 'id';
    protected $subqueryField = 'fairyId';
    
    public function setUp()
    {
        $this->conditions = $this->quickMock('\PHPixie\ORM\Conditions');
        $this->mappers    = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->steps      = $this->quickMock('\PHPixie\ORM\Steps');
        
        $this->conditionsMapper = $this->quickMock('\PHPixie\ORM\Mappers\Conditions');
        $this->method($this->mappers, 'conditions', $this->conditionsMapper, array());
        
        $this->subqueryStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\In\Strategy\Subquery');
        $this->multiqueryStrategy = $this->quickMock('\PHPixie\ORM\Planners\Planner\In\Strategy\Multiquery');
        
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
     * @covers ::items
     * @covers ::<protected>
     */
    public function testItems()
    {
        $query = $this->getItemsQuery();
        $modelName = 'pixie';
        $items = array($this->getDatabaseModelQuery());
        $plan = $this->getStepsPlan();
        
        $callback = array($this->plannerMock(), 'items');
        $params = array(
            $query,
            $modelName,
            $items,
            $plan
        );
        
        $this->prepareItemsTest($query, $modelName, $items, $plan, 'and', false);
        call_user_func_array($callback, $params);
        
        $this->prepareItemsTest($query, $modelName, $items, $plan, 'or', true);
        $params = array_merge($params, array('or', true));
        call_user_func_array($callback, $params);
        
    }
    
    /**
     * @covers ::databaseModelQuery
     * @covers ::<protected>
     */
    public function testDatabaseModelQuery()
    {
        $query = $this->getItemsQuery();
        $modelQuery = $this->getDatabaseModelQuery();
        $plan = $this->getStepsPlan();
        
        $callback = array($this->plannerMock(), 'databaseModelQuery');
        $params = array(
            $query,
            $this->queryField,
            $modelQuery,
            $this->subqueryField,
            $plan
        );
        
        $this->prepareDatabaseModelQueryTest($query, $modelQuery, $plan, 'and', false);
        call_user_func_array($callback, $params);
        
        $this->prepareDatabaseModelQueryTest($query, $modelQuery, $plan, 'or', true);
        $params = array_merge($params, array('or', true));
        call_user_func_array($callback, $params);
        
    }
    
    /**
     * @covers ::itemIds
     * @covers ::<protected>
     */
    public function testItemsIds()
    {
        $query = $this->getItemsQuery();
        $repository = $this->getRepository();
        $plan = $this->getStepsPlan();
        
        $items = array($this->getDatabaseModelQuery());
        
        $callback = array($this->plannerMock(), 'itemIds');
        $params = array(
            $query,
            $this->queryField,
            $repository,
            $items,
            $plan
        );
        
        $this->prepareItemIdsTest($query, $repository, $items, $plan, 'and', false);
        call_user_func_array($callback, $params);
        
        $this->prepareItemIdsTest($query, $repository, $items, $plan, 'or', true);
        $params = array_merge($params, array('or', true));
        call_user_func_array($callback, $params);
        
    }
    
    /**
     * @covers ::<protected>
     * @covers ::result
     */
    public function testResult()
    {
		$query = $this->getItemsQuery();
		$resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
		$plan = $this->getStepsPlan();
		
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
        $this->subqueryTest('subquery');
        $this->subqueryTest('multiquery', false);
        $this->subqueryTest('multiquery', true, false);
    }
    
    /**
     * @covers ::buildSubqueryStrategy
     * @covers ::<protected>
     */
    public function testBuildSubqueryStrategy()
    {
        $this->assertStrategy('subquery', '\PHPixie\ORM\Planners\Planner\In\Strategy\Subquery');
    }
    
    /**
     * @covers ::buildMultiqueryStrategy
     * @covers ::<protected>
     */
    public function testBuildMultiqueryStrategy()
    {
        $this->assertStrategy('multiquery', '\PHPixie\ORM\Planners\Planner\In\Strategy\Multiquery', array(
            'steps' => $this->steps
        ));
    }
    
    protected function subqueryTest($strategy, $queryIsSql = true, $subqueryIsSame = true)
    {
        $query = $this->getItemsQuery();
        $subquery = $this->getItemsQuery();
        $plan = $this->quickMock('\PHPixie\ORM\Plans\Plan');
        
        $this->prepareConnections($query, $subquery, $queryIsSql, $subqueryIsSame);
        
        $callback = array($this->plannerMock(), 'subquery');
        $params = array(
            $query,
            $this->queryField,
            $subquery,
            $this->subqueryField,
            $plan
        );
            
        $this->prepareSubquery($strategy, $query, $subquery, $plan, 'and', false);
        call_user_func_array($callback, $params);

        $this->prepareSubquery($strategy, $query, $subquery, $plan, 'or', true);
        $params = array_merge($params, array('or', true));
        call_user_func_array($callback, $params);
	}
    
    protected function prepareDatabaseModelQueryTest($query, $modelQuery, $plan, $logic, $negate, $queryAt = 0)
    {
        $loaderPlan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Query\Loader');
        $this->method($modelQuery, 'planFind', $loaderPlan, array(), $queryAt);
        
        $requiredPlan = $this->getStepsPlan();
        $this->method($loaderPlan, 'requiredPlan', $requiredPlan, array(), 0);
        
        $this->method($plan, 'appendPlan', null, array($requiredPlan), 0);
        
        $resultStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result');
        
        $subquery = $this->getItemsQuery();
        $this->method($loaderPlan, 'queryStep', $resultStep, array(), 1);
        $this->method($resultStep, 'query', $subquery, array(), 0);
        
        $this->prepareConnections($query, $subquery, true, true, 0);
        $this->prepareSubquery('subquery', $query, $subquery, $plan, $logic, $negate);
    }

    protected function prepareItemsTest($query, $modelName, $items, $plan, $logic, $negate)
    {
        $inCondition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In');
        $this->method($this->conditions, 'in', $inCondition, array(
            $modelName,
            $items
        ), 0);
        
        $this->method($inCondition, 'setLogic', null, array($logic), 0);
        $this->method($inCondition, 'setIsNegated', null, array($negate), 1);
        
        $this->method($this->conditionsMapper, 'map', null, array(
            $query,
            $modelName,
            array($inCondition),
            $plan
        ), 0);
        
    }
    
    protected function prepareItemIdsTest($query, $repository, $items, $plan, $logic, $negate)
    {
        $config = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
        $config->idField = $this->subqueryField;
        
        $modelQuery = $this->getDatabaseModelQuery();
        
        $this->method($repository, 'config', $config, array(), 0);
        $this->method($repository, 'query', $modelQuery, array(), 1);
        
        $this->method($modelQuery, 'in', $modelQuery, array($items), 0);
        
        $this->prepareDatabaseModelQueryTest($query, $modelQuery, $plan, $logic, $negate, 1);
    }
    
    protected function prepareConnections($query, $subquery, $queryIsSql = true, $subqueryIsSame = true, $at = null)
    {
        $queryConnection = $this->getConnection($queryIsSql);
        if($subqueryIsSame) {
            $subqueryConnection = $queryConnection;
        }else{
            $subqueryConnection = $this->getConnection();
        }
        
        $this->method($query, 'connection', $queryConnection, array(), $at);
        $this->method($subquery, 'connection', $subqueryConnection, array(), $at);
    }
    
    protected function prepareResultTest($query, $resultStep, $plan, $logic, $negate)
    {
        $placeholder = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection\Placeholder');
        
        $this->method($query, 'addPlaceholder', $placeholder, array($logic, $negate), 0);
        
        $inStep = $this->quickMock('\PHPixie\ORM\Steps\Step\In');
        $this->method($this->steps, 'in', $inStep, array($placeholder, 'pixie', $resultStep, 'fairy'), 0);
        
        $this->method($plan, 'add', null, array($inStep), 0);
	}
    
    protected function prepareSubquery($strategy, $query, $subquery, $plan, $logic, $negate)
    {
        $strategy = $strategy.'Strategy';
        $this->method($this->$strategy, 'in', null, array(
            $query,
            $this->queryField,
            $subquery,
            $this->subqueryField,
            $plan,
            $logic,
            $negate
        ), 0);
    }
	
    protected function getStepsPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    protected function getConnection($isSql = false)
    {
        if($isSql) {
            return $this->abstractMock('\PHPixie\Database\Type\SQL\Connection');
        }
        
        return $this->abstractMock('\PHPixie\Database\Connection');
    }
    
    protected function getItemsQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Items');
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function getDatabaseModelQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function planner()
    {
        return new \PHPixie\ORM\Planners\Planner\In(
            $this->conditions,
            $this->mappers,
            $this->steps
        );
    }
    
    protected function plannerMock()
    {
        $mock = $this->getMock('\PHPixie\ORM\Planners\Planner\In', array(
            'buildSubqueryStrategy',
            'buildMultiqueryStrategy'
        ), array(
            $this->conditions,
            $this->mappers,
            $this->steps
        ));
        
        $this->method($mock, 'buildSubqueryStrategy', $this->subqueryStrategy, array());
        $this->method($mock, 'buildMultiqueryStrategy', $this->multiqueryStrategy, array());
        
        return $mock;
    }
}
