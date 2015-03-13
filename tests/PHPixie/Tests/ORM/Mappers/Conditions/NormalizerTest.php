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
        $this->normalizeInTest(false, false);
        $this->normalizeInTest(false, true);
        $this->normalizeInTest(true, false);
        $this->normalizeInTest(true, true);
    }
    
    protected function normalizeInTest($withQueries, $withEntities)
    {
        $modelName = 'pixie';
        
        $inCondition = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In');
        $this->method($inCondition, 'modelName', $modelName, array(), 0);
        
        $queries = array();
        if($withQueries) {
            $queries[]= $this->getDatabaseQuery();
            $queries[]= $this->getDatabaseQuery();
        }
        
        $entities = array();
        if($withEntities) {
            $entities[5]= $this->getDatabaseEntity();
            $entities[6]= $this->getDatabaseEntity();
            $ids = array(5, 6);
        }
        
        $items = array_merge($queries, $entities);
        $this->method($inCondition, 'items', $items, array(), 1);
        
        $inGroup = $this->getRelationshipGroup();
        $this->method($this->conditions, 'group', $inGroup, array(), 0);
        
        $this->prepareCopyLogicAndNegated($inCondition, $inGroup, 2);
        
        foreach($queries as $key => $query) {
            $queryGroup = $this->getGroup();
            $this->method($this->conditions, 'group', $queryGroup, array(), 1+$key);
            
            $this->prepareSetLogicAndNegated($queryGroup, 'or', false);
            
            $conditions = array($this->getGroup());
            $this->method($query, 'getConditions', $conditions, array(), 0);
            $this->method($queryGroup, 'setConditions', null, array($conditions), 2);
            
            $this->method($inGroup, 'add', null, array($queryGroup), 2+$key);
        }
        
        foreach($entities as $key => $entity) {
            $this->method($entity, 'id', $key, array(), 0);
        }
        
        if(!empty($entities) || empty($items)) {
            $config = $this->getDatabaseConfig();
            $this->method($this->databaseModel, 'config', $config, array($modelName), 0);
            $config->idField = 'id';
            
            $operatorCondition = $this->getOperatorCondition();
            $this->method($this->conditions, 'operator', $operatorCondition, array(
                'id',
                'in',
                array(array_keys($entities))
            ), count($queries)+1);
            
            $this->prepareSetLogicAndNegated($operatorCondition, 'or', false);
            $this->method($inGroup, 'add', null, array($operatorCondition), count($queries)+2);
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