<?php

namespace PHPixieTests\ORM\Mappers;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Group
 */
class GroupTest extends \PHPixieTests\AbstractORMTest
{
    protected $repositories;
    protected $relationships;
    protected $relationshipMap;
    protected $planners;
    protected $inPlanner;
    protected $modelName = 'fairy';
    
    protected $groupMapper;
    
    public function setUp()
    {
        $this->repositories = $this->quickMock('\PHPixie\ORM\Repositories');
        $this->relationships = $this->quickMock('\PHPixie\ORM\Relationships');
        $this->relationshipMap = $this->quickMock('\PHPixie\ORM\Relationships\Map');
        $this->method($this->relationships, 'map', $this->relationshipMap, array());
        
        $this->planners = $this->quickMock('\PHPixie\ORM\Planners');
        $this->inPlanner = $this->quickMock('\PHPixie\ORM\Planners\Planner\In');
        $this->method($this->planners, 'in', $this->inPlanner, array());
        
        $this->groupMapper = new \PHPixie\ORM\Mappers\Group(
            $this->repositories,
            $this->relationships,
            $this->planners
        );
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::mapDatabaseQuery
     * @covers ::<protected>
     */
    
    public function testMapDatabaseQuery()
    {
        $query = $this->getDatabaseQuery();
        $plan = $this->getStepsPlan();
        $conditions = $this->prepareMapConditions($query, $plan);
        $this->groupMapper->mapDatabaseQuery($query, $this->modelName, $conditions, $plan);
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Mapper');
        $this->groupMapper->mapDatabaseQuery($query, $this->modelName, array('test'), $plan);
    }
    
    /**
     * @covers ::mapEmbeddedContainer
     * @covers ::<protected>
     */
    public function testMapSubdocument()
    {
        $prefix = 'trixie';
        $subdocument = $this->getEmbeddedContainer();
        $plan = $this->getStepsPlan();
        $conditions = $this->prepareMapConditions($subdocument, $plan, true);
        $this->groupMapper->mapEmbeddedContainer($subdocument, $this->modelName, $conditions, $plan, $prefix);
        
        $invalid = array('test', $this->inCondition('and', false, array(5)));
        foreach($invalid as $condition) {
            $except = false;
            try{
                $this->groupMapper->mapEmbeddedContainer($subdocument, $this->modelName, array($condition), $plan, $prefix);        
            }catch(\PHPixie\ORM\Exception\Mapper $e) {
                $except = true;
            }
            
            $this->assertEquals(true, $except);
        }
        
    }
    
    protected function prepareMapConditions($builder, $plan, $isEmbeddedContainer = false)
    {
        $operatorParams = array('or', false, 'a', '>', array(1));
        $operator = call_user_func_array(array($this, 'operatorCondition'), $operatorParams);
        
        $conditions = array();
        
        
        $conditions[] = $operator;
        $this->method($builder, 'addOperatorCondition', null, $operatorParams, 0);
        
        
        $conditions[] = $this->groupCondition('and', true, array($operator));
        $this->method($builder, 'startGroup', null, array('and', true), 1);
        $this->method($builder, 'addOperatorCondition', null, $operatorParams, 2);
        $this->method($builder, 'endGroup', null, array(), 3);
        
        $relationshipGroup = $this->relationshipCondition('and', true, 'pixie', array($operator));
        
        $conditions[] = $relationshipGroup;
        $side = $this->getSide();
        $this->method($this->relationshipMap, 'getSide', $side, array($this->modelName, 'pixie'), 0);
        $this->method($side, 'relationshipType', 'oneToOne', array(), 0);
        
        $relationship = $this->getRelationship();
        $this->method($this->relationships, 'get', $relationship, array('oneToOne'), 0);
        
        $handler = $this->getHandler($isEmbeddedContainer);
        $this->method($relationship, 'handler', $handler, array(), 0);
        
        if(!$isEmbeddedContainer) {
            $this->method($handler, 'mapQuery', null, array($builder, $side, $relationshipGroup, $plan), 0);
        }else{
            $this->method($handler, 'mapEmbeddedContainer', null, array($builder, $side, $relationshipGroup, $plan), 0);
        
        }
        
        if(!$isEmbeddedContainer) {
            $items = array(5);
            $repository = $this->getRepository();
            $this->method($this->repositories, 'get', $repository, array($this->modelName), 0);
            
            $config = $this->getDatabaseModelConfig();
            $config->idField = 'id';
            $this->method($repository, 'config', $config, array(), 0);
            
            $conditions[] = $this->inCondition('or', true, $items);
            $collection = $this->getCollection();
            $this->method($this->planners, 'collection', $collection, array($this->modelName, $items), 0);
            $this->method($this->inPlanner, 'collection', null, array($builder, 'id', $collection, 'id', $plan, 'or', true), 0);
        }
        
        return $conditions;
    }
    
    protected function operatorCondition($logic, $negated, $field, $operator, $values)
    {
        $condition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Operator');

        $this->method($condition, 'field', $field, array());
        $this->method($condition, 'operator', $operator, array());
        $this->method($condition, 'values', $values, array());
        
        return $this->prepareCondition($condition, $logic, $negated);
    }
    
    protected function inCondition($logic, $negated, $items)
    {
        $group = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In');
        $this->method($group, 'items', $items);
        return $this->prepareCondition($group, $logic, $negated);
    }
    
    protected function groupCondition($logic, $negated, $conditions)
    {
        $group = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Group');
        return $this->prepareGroup($group, $logic, $negated, $conditions);
    }
    
    protected function relationshipCondition($logic, $negated, $relationship, $conditions)
    {
        $group = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Group\Relationship');
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