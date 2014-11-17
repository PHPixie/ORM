<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Mapper
 */
class MapperTest extends \PHPixieTests\AbstractORMTest
{
    protected $plans;
    protected $steps;
    protected $loaders;
    protected $repositories;
    protected $groupMapper;
    protected $cascadeMapper;
    
    protected $mapper;
    
    protected $modelName = 'fairy';
    
    protected $stepClasses = array(
        'count' => 'Query\Count'
    );
    
    protected $plansClasses = array(
        'count' => 'Query\Count',
        'steps' => 'Steps'
    );
    
    public function setUp()
    {
        $this->plans = $this->quickMock('\PHPixie\ORM\Plans');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->repositories = $this->quickMock('\PHPixie\ORM\Repositories');
        $this->groupMapper = $this->quickMock('\PHPixie\ORM\Mapper\Group');
        $this->cascadeMapper = $this->quickMock('\PHPixie\ORM\Mapper\Cascade');
        
        $this->mapper = new \PHPixie\ORM\Mapper(
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->repositories,
            $this->groupMapper,
            $this->cascadeMapper
        );
    }
    
    /**
     * @covers ::mapCount
     * @covers ::<protected>
     */
    public function testMapCount()
    {
        $query = $this->getQuery();
        
        $databaseQuery = $this->prepareDatabaseQuery('count');
        $step  = $this->prepareStep('count', array($databaseQuery));
        $plan  = $this->preparePlan('count', array($step));
        
        $this->prepareMapConditons($query, $databaseQuery, $plan);
        $this->assertSame($plan, $this->mapper->mapCount($query));
    }
    
    protected function prepareMapConditons($query, $databaseQuery, $plan)
    {
        $conditions = array('test');
        $this->method($query, 'getConditions', $conditions, array());
        
        $requiredPlan = $this->getPlan('steps');
        $this->method($plan, 'requiredPlan', $requiredPlan, array(), 0);
        
        $this->method($this->groupMapper, 'mapConditions', null, array(
            $databaseQuery,
            $conditions,
            $this->modelName,
            $requiredPlan
        ), 0);

    }
    
    protected function prepareDatabaseQuery($type)
    {
        $repository = $this->prepareRepository($this->modelName);
        $query = $this->getDatabaseQuery($type);
        $this->method($repository, 'database'.ucfirst($type).'Query', $query, array(), 0);
        return $query;
    }

    protected function prepareStep($type, $params)
    {
        $step = $this->getStep($this->stepClasses[$type]);
        $this->method($this->steps, $type, $step, $params, 0);
        return $step;
    }
    
    protected function preparePlan($type, $params)
    {
        $plan = $this->getPlan($this->plansClasses[$type]);
        $this->method($this->plans, $type, $plan, $params, 0);
        return $plan;
    }
    
    protected function prepareRepository($name)
    {
        $repository=$this->getRepository();
        $this->method($this->repositories, 'get', $repository, array($name), 0);
        return $repository;
    }
    
    protected function getQuery()
    {
        $query = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
        $this->method($query, 'modelName', $this->modelName, array());
        return $query;
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function getDatabaseQuery($type)
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\\'.ucfirst($type));
    }
                      
    protected function getStep($class)
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Step\\'.ucfirst($class));
    }
    
    protected function getPlan($class)
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\\'.ucfirst($class));
    }
}