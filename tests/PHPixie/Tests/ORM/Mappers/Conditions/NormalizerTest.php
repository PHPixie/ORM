<?php

namespace PHPixie\Tests\ORM\Mappers\Group;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Conditions\Normalizer
 */
class NormalizerTest extends \PHPixie\Test\Testcase
{
    protected $conditions;
    protected $models;
    
    protected $normalizer;
    
    protected $databaseModel;
    
    public function setUp()
    {
        $this->conditions = $this->quickMock('\PHPixie\ORM\Conditions');
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        
        $this->normalizer = new \PHPixie\ORM\Mappers\Conditions\Normalizer(
            $this->conditions,
            $this->models
        );
        
        $this->databaseModel = $this->quickMock('\PHPixie\ORM\Models\Type\Database');
        $this->method($this->models, 'database', $this->databaseModel, array());
    }

    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::normalizeIn
     * @covers ::<protected>
     */
    public function testNormalizeIn()
    {
        $this->normalizeInTest(false, false, false);
        $this->normalizeInTest(false, false, true);
        $this->normalizeInTest(true, true, false);
        $this->normalizeInTest(true, true, true);
        $this->normalizeInTest(true, false, false, true);
    }
    
    protected function normalizeInTest($withIds, $withQueries, $withEntities, $singleId = false)
    {
        $modelName = 'pixie';
        
        $inCondition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In');
        $this->method($inCondition, 'modelName', $modelName, array(), 0);
        
        $idField = 'pixieId';
        $config = $this->getDatabaseConfig();
        $this->method($this->databaseModel, 'config', $config, array($modelName), 0);
        $config->idField = $idField;
        
        $ids = array();
        if($withIds) {
            $ids[]=5;
            if(!$singleId) {
                $ids[]=6;
            }
        }
        
        $queries = array();
        if($withQueries) {
            $queries[]= $this->getDatabaseQuery();
            $queries[]= $this->getDatabaseQuery();
            $queries[]= $this->getDatabaseQuery();
        }
        
        $entities = array();
        if($withEntities) {
            $entities[5]= $this->getDatabaseEntity();
            $entities[6]= $this->getDatabaseEntity();
        }
        
        $items = array_merge($ids, $queries, array_values($entities));
        $this->method($inCondition, 'items', $items, array(), 1);
        
        $conditionsAt = 0;
        
        $inGroup = $this->getRelationshipGroup();
        $this->method($this->conditions, 'group', $inGroup, array(), $conditionsAt++);
        
        $this->prepareCopyLogicAndNegated($inCondition, $inGroup, 2);
        $inGroupAt = 2;
        
        foreach($queries as $key => $query) {
            
            $limit = $key === 0 ? 5 : null;
            $offset = $key === 1 ? 5 : null;
            
            $this->method($query, 'getLimit', $limit, array());
            $this->method($query, 'getOffset', $offset, array());
            
            if($key !== 2) {
                $condition = $this->getSubqueryCondition();
                $this->method($this->conditions, 'subquery', $condition, array(
                    $idField,
                    $query,
                    $idField
                ), $conditionsAt++);
                $this->prepareSetLogicAndNegated($condition, 'or', false);
                
            }else{
                $condition = $this->getGroup();
                $this->method($this->conditions, 'group', $condition, array(), $conditionsAt++);
                
                $conditions = array($this->getGroup());
                $this->method($query, 'getConditions', $conditions, array());
                $this->method($condition, 'setConditions', null, array($conditions));
                $this->prepareSetLogicAndNegated($condition, 'or', false, 1);    
            }
                        
            $this->method($inGroup, 'add', null, array($condition), $inGroupAt++);
        }
        
        foreach($entities as $key => $entity) {
            $this->method($entity, 'id', $key, array(), 0);
        }
        
        $allIds = array_merge($ids, array_keys($entities));
        if(!empty($allIds)) {
            if(count($allIds) === 1) {
                $operator = '=';
                $values   = $allIds;
            }else{
                $operator = 'in';
                $values   = array($allIds);   
            }
            $operatorCondition = $this->getOperatorCondition();
            $this->method($this->conditions, 'operator', $operatorCondition, array(
                $idField,
                $operator,
                $values
            ), $conditionsAt++);
            
            $this->prepareSetLogicAndNegated($operatorCondition, 'or', false);
            $this->method($inGroup, 'add', null, array($operatorCondition), $inGroupAt++);
        }
        
        if(empty($items)) {
            $operatorCondition = $this->getOperatorCondition();
            $this->method($this->conditions, 'operator', $operatorCondition, array(
                $idField,
                '=',
                array(null)
            ), $conditionsAt++);
            
            $this->method($inGroup, 'add', null, array($operatorCondition), $inGroupAt++);
        }
        
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
        $this->method($condition, 'setLogic', null, array($logic), $at++);
        $this->method($condition, 'setIsNegated', null, array($negated), $at++);
    }
    
    protected function getGroup()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection\Group');
    }
    
    protected function getOperatorCondition()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Condition\Field\Operator');
    }
    
    protected function getSubqueryCondition()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Condition\Field\Subquery');
    }
    
    protected function getRelationshipGroup()
    {
        return $this->quickMock('\PHPixie\ORM\Conditions\Condition\Collection\RelatedTo\Group');
    }
    
    protected function getDatabaseConfig()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Config');
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