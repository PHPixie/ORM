<?php

namespace PHPixie\Tests\ORM\Mappers;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Conditions
 */
class ConditionsTest extends \PHPixie\Test\Testcase
{
    protected $mappers;
    protected $planners;
    protected $relationships;
    protected $relationshipMap;
    
    protected $conditionsMapper;
    
    protected $inPlanner;
    protected $mapperMocks = array();
    protected $modelName = 'fairy';
    
    public function setUp()
    {
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Maps\Map\Relationship');
        
        $this->conditionsMapper = new \PHPixie\ORM\Mappers\Conditions(
            $this->mappers,
            $this->planners,
            $this->relationships,
            $this->relationshipMap
        );
        
        $this->inPlanner = $this->quickMock('\PHPixie\ORM\Planners\Planner\In');
        $this->method($this->planners, 'in', $this->inPlanner, array());
        
        foreach(array('optimizer', 'normalizer') as $type) {
            $method = 'conditions'.ucfirst($type);
            $mapper = $this->quickMock('\PHPixie\ORM\Mappers\Conditions\\'.ucfirst($type));
            $this->mapperMocks[$method] = $mapper;
            $this->method($this->mappers, $method, $mapper, array());
        }
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::map
     * @covers ::<protected>
     */
    public function testMapDatabaseQuery()
    {
        $this->mapTest('query');
        $this->mapTest('embedded');
    }
    
    protected function mapTest($type = 'query')
    {
        if($type === 'query') {
            $builder = $this->getDatabaseQuery();
        }else{
            $builder = $this->getEmbeddedContainer();
        }
        
        $plan = $this->getStepsPlan();
        $conditions = $this->prepareMap($builder, $plan, $type === 'query');
        $this->conditionsMapper->map($builder, $this->modelName, $conditions, $plan);
        
        $conditions = $this->prepareInvalidCondition();
        
        try{
            $this->conditionsMapper->map($builder, $this->modelName, $conditions, $plan);
        }catch(\PHPixie\ORM\Exception\Mapper $e) {
            $except = true;
        }

        $this->assertEquals(true, $except);
    }
    
    protected function prepareMap($builder, $plan, $isQuery = true)
    {
        $operatorParams = array('or', false, 'a', '>', array(1));
        $operator = call_user_func_array(array($this, 'operatorCondition'), $operatorParams);
        
        $optimizedConditions = array();
        
        $optimizedConditions[] = $operator;
        $this->method($builder, 'addOperatorCondition', null, $operatorParams, 0);
        
        $optimizedConditions[] = $this->conditionCollection('and', true, array($operator));
        $this->method($builder, 'startConditionGroup', null, array('and', true), 1);
        $this->method($builder, 'addOperatorCondition', null, $operatorParams, 2);
        $this->method($builder, 'endGroup', null, array(), 3);
        
        $subquery = $this->getDatabaseQuery();
        $subqueryCondition = $this->subqueryCondition('or', true, 'id', $subquery, 'pixieId');
        $optimizedConditions[] = $subqueryCondition;
        
        $this->method($this->inPlanner, 'databaseModelQuery', null, array(
            $builder,
            'id',
            $subquery,
            'pixieId',
            $plan,
            'or',
            true
        ), 0);
        
        $relatedToCondition = $this->relatedToCollection('and', true, 'pixie', array($operator));
        
        $optimizedConditions[] = $relatedToCondition;
        $side = $this->getSide();
        $this->method($this->relationshipMap, 'get', $side, array($this->modelName, 'pixie'), 0);
        $this->method($side, 'relationshipType', 'oneToOne', array(), 0);
        
        $relationship = $this->getRelationship();
        $this->method($this->relationships, 'get', $relationship, array('oneToOne'), 0);
        
        $handler = $this->getHandler(!$isQuery);
        $this->method($relationship, 'handler', $handler, array(), 0);
        
        
        if($isQuery) {
            $this->method($handler, 'mapDatabaseQuery', null, array($builder, $side, $relatedToCondition, $plan), 0);
            
        }else{
            $this->method($handler, 'mapEmbeddedContainer', null, array($builder, $side, $relatedToCondition, $plan), 0);
        }
        
        if($isQuery) {
            $items = array($this->getDatabaseQuery());
            $inCondition = $this->inCondition('or', true, $items);
            
            $optimizedConditions[] = $inCondition;
            
            $normalizedCondition = $this->conditionCollection('and', true, array($operator));
            $this->method($builder, 'startConditionGroup', null, array('and', true), 4);
            $this->method($builder, 'addOperatorCondition', null, $operatorParams, 5);
            $this->method($builder, 'endGroup', null, array(), 6);
            
            $this->method(
                $this->mapperMocks['conditionsNormalizer'],
                'normalizeIn',
                $normalizedCondition,
                array($inCondition),
                0
            );
        }
        
        $conditions = array($this->conditionCollection('and', false, array()));
        $this->method(
            $this->mapperMocks['conditionsOptimizer'],
            'optimize',
            $optimizedConditions,
            array($conditions),
            0
        );
        
        return $conditions;
    }
    
    protected function prepareInvalidCondition()
    {
        $optimizedConditions = array(new \stdClass());
        $conditions = array($this->conditionCollection('and', false, array()));
        
        $this->method($this->mapperMocks['conditionsOptimizer'], 'optimize', $optimizedConditions, array($conditions), 0);
        
        return $conditions;
    }
    
    protected function operatorCondition($logic, $negated, $field, $operator, $values)
    {
        $condition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Field\Operator');

        $this->method($condition, 'field', $field, array());
        $this->method($condition, 'operator', $operator, array());
        $this->method($condition, 'values', $values, array());
        
        return $this->prepareCondition($condition, $logic, $negated);
    }
    
    protected function inCondition($logic, $negated, $items)
    {
        $inCondition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In');
        $this->method($inCondition, 'items', $items);
        return $this->prepareCondition($inCondition, $logic, $negated);
    }
    
    protected function subqueryCondition($logic, $negated, $field, $subquery, $subqueryField)
    {
        $condition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Field\Subquery');
        $this->method($condition, 'field', $field);
        $this->method($condition, 'subquery', $subquery);
        $this->method($condition, 'subqueryField', $subqueryField);
        return $this->prepareCondition($condition, $logic, $negated);
    }
    
    protected function conditionCollection($logic, $negated, $conditions)
    {
        $group = $this->abstractMock('\PHPixie\ORM\Conditions\Condition\Collection');
        return $this->prepareGroup($group, $logic, $negated, $conditions);
    }
    
    protected function relatedToCollection($logic, $negated, $relationship, $conditions)
    {
        $group = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection\RelatedTo');
        $this->method($group, 'relationship', $relationship, array());
        return $this->prepareGroup($group, $logic, $negated, $conditions);
    }
    
    protected function prepareGroup($group, $logic, $negated, $conditions)
    {
        $this->method($group, 'conditions', $conditions);
        return $this->prepareCondition($group, $logic, $negated);
    }
    
    protected function prepareCondition($condition, $logic, $negated)
    {
        $this->method($condition, 'logic', $logic, array());
        $this->method($condition, 'isNegated', $negated, array());
        return $condition;
    }
    
    protected function getRelationship()
    {
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Relationship\Side');
    }
    
    protected function getHandler($embedded = false)
    {
        if($embedded)
             return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Embedded');
        
        return $this->abstractMock('\PHPixie\ORM\Relationships\Relationship\Handler\Mapping\Database');
    }
    
    protected function getDatabaseQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Items');
    }
    
    protected function getEmbeddedContainer()
    {
        return $this->quickMock('\PHPixie\Database\Type\Document\Conditions\Builder\Container');
    }
    
    protected function getStepsPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    protected function getCollection()
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Collection');
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function getDatabaseModelConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
    }
}