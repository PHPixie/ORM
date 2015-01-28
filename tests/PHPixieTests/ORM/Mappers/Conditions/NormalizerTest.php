<?php

namespace PHPixieTests\ORM\Mappers\Group;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Conditions\Normalizer
 */
class NormalizerTest extends \PHPixieTests\AbstractORMTest
{
    protected $conditions;
    protected $models;
    
    protected $normalizer;
    
    protected $databaseModel;
    
    public function setUp()
    {
        $this->conditions = $this->quickMock('\PHPixie\ORM\Conditions');
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        
        $this->normalizer = \PHPixie\ORM\Mappers\Conditions\Normalizer(
            $this->conditions,
            $this->models
        );
        
        $this->databaseModel = $this->quickMock('\PHPixie\ORM\Models\Type\Database');
        $this->model($this->models, 'database', $this->databaseModel, array());
    }
    
    /**
     * @covers ::normalizeIn
     * @covers ::<protected>
     */
    public function testNormalizeIn()
    {
        $inCondition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In');
        $queries = array(
            $this->getDatabaseQuery(),
            $this->getDatabaseQuery()
        );
        
        $entities = array(
            $this->getDatabaseEntity(),
            $this->getDatabaseEntity(),
        );
        
        $items = array_merge($queries, $entities);
        $this->method($inCondition, 'items', $items, array(), 0);
        
        $inGroup = $this->getRelationshipGroup();
        $this->method($this->conditions, 'relationshipGroup', $inGroup, array(), 0);
        
        $this->prepareCopyLogicAndNegated($inCondition, $inGroup);
        
        $ids = array(5, 6);
        
        foreach($queries as $key => $query) {
            $queryGroup = $this->getGroup();
            $this->method($this->conditions, 'group', $queryGroup, array(), 1 + $key*3);
            
            $this->prepareSetLogicAndNegated($queryGroup, 'or', false);
            
            $conditions = array($this->getGroup());
            $this->method($query, 'conditions', $conditions, array(), 0);
            $this->method($queryGroup, 'setConditions', null, array($conditions), 2);
            
            $this->method($inGroup, 'add', null, array($queryGroup), 3 + $key*3);
        }
        
        foreach($entities as $key => $entity) {
            $this->method($entity, 'id', $ids[$key], array(), 0);
        }
        
        $config = $this->getDatabaseConfig();
        $this->method($this->databaseModel, 'config', $config, array($modelName), 0);
        $config->idField = 'id';
        
        $operatorCondition = $this->getOperatorCondition();
        $this->method($this->conditions, 'operator', $operatorCondition, array(
            'id',
            'in',
            array($ids)
        ), 5);
        
        $this->prepareSetLogicAndNegated($operatorCondition, 'or', false);
        $this->method($inGroup, 'add', null, array($queryGroup), 3 + $key*3);
        
        $this->assertSame($inGroup, $this->normalizer->normalizeIn($inCondition));
    }
    
    protected function prepareCopyLogicAndNegated($source, $target, $sourceAt = 0, $targetAt = 0)
    {
        $this->method($source, 'logic', 'or', array(), $sourceAt++);
        $this->method($source, 'isNegated', true, array(), $sourceAt++);
        
        $this->prepareSetLogicAndNegated($target, 'or', true, $targetAt++);
    }
    
    protected function prepareSetLogicAndNegated($condition, $logic, $negated, $at = 0)
    {
        $this->method($target, 'setLogic', null, array($logic), $at++);
        $this->method($target, 'setIsNegated', null, array($negated), $at++);
    }
    
    protected function getGroup()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection\Group');
    }
    
    protected function getRelationshipGroup()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection\Group\Relationship');
    }
    
    protected function getDatabaseQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function getDatabaseEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
}