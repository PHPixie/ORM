<?php

namespace PHPixie\Tests\ORM\Mappers;

/**
 * @coversDefaultClass \PHPixie\ORM\Mappers\Query
 */
class QueryTest extends \PHPixie\Test\Testcase
{
    protected $mappers;
    protected $loaders;
    protected $models;
    protected $plans;
    protected $steps;
        
    protected $queryPropertyMapper;
    
    protected $modelName = 'fairy';
    
    protected $databaseModel;
    protected $conditionsMapper;
    protected $preloadMapper;
    protected $updateMapper;
    protected $cascadeDeleteMapper;
    
    protected $stepClasses = array(
        'query' => 'Query',
        'count' => 'Query\Count',
        'reusableResult' => 'Query\Result\Reusable'
    );
    
    protected $planClasses = array(
        'query' => 'Query',
        'count' => 'Query\Count',
        'steps' => 'Steps',
        'loader' => 'Query\Loader'
    );
    
    protected $loaderClasses = array(
        'preloadingProxy' => 'Proxy\Preloading',
        'cachingProxy' => 'Proxy\Caching',
        'reusableResult' => 'Repository\ReusableResult',
    );
    
    public function setUp()
    {
        $this->mappers = $this->quickMock('\PHPixie\ORM\Mappers');
        $this->loaders = $this->quickMock('\PHPixie\ORM\Loaders');
        $this->models = $this->quickMock('\PHPixie\ORM\Models');
        $this->plans = $this->quickMock('\PHPixie\ORM\Plans');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        
        $this->databaseModel = $this->quickMock('\PHPixie\ORM\Models\Type\Database');
        $this->method($this->models, 'database', $this->databaseModel, array());
        
        $this->conditionsMapper = $this->quickMock('\PHPixie\ORM\Mappers\Conditions');
        $this->preloadMapper = $this->quickMock('\PHPixie\ORM\Mappers\Preload');
        $this->updateMapper = $this->quickMock('\PHPixie\ORM\Mappers\Update');
        $this->cascadeDeleteMapper = $this->quickMock('\PHPixie\ORM\Mappers\Cascade\Mapper\Delete');
        
        $this->method($this->mappers, 'conditions', $this->conditionsMapper, array());
        $this->method($this->mappers, 'preload', $this->preloadMapper, array());
        $this->method($this->mappers, 'update', $this->updateMapper, array());
        $this->method($this->mappers, 'cascadeDelete', $this->cascadeDeleteMapper, array());
        
        $this->queryMapper = new \PHPixie\ORM\Mappers\Query(
            $this->mappers,
            $this->loaders,
            $this->models,
            $this->plans,
            $this->steps
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
     * @covers ::mapCount
     * @covers ::<protected>
     */
    public function testMapCount()
    {
        $this->mapCountTest(false);
        $this->mapCountTest(true);
    }
    
    protected function mapCountTest($withQueryParams = false)
    {
        $query = $this->getQuery();
        
        $databaseQuery = $this->prepareDatabaseQuery('count');
        $step  = $this->prepareStep('count', array($databaseQuery));
        $plan  = $this->preparePlan('count', array($step));
        
        $this->prepareMapCommon($query, $databaseQuery, $plan, $withQueryParams);
        $this->assertSame($plan, $this->queryMapper->mapCount($query));
    }
    
    /**
     * @covers ::mapUpdate
     * @covers ::<protected>
     */
    public function testMapUpdate()
    {
        $this->mapUpdateTest(false);
        $this->mapUpdateTest(true);
    }
    
    protected function mapUpdateTest($withQueryParams = false)
    {
        $query = $this->getQuery();
        $update = $this->getUpdate();
        
        $databaseQuery = $this->prepareDatabaseQuery('update');
        $step  = $this->prepareStep('query', array($databaseQuery));
        $plan  = $this->preparePlan('query', array($step));
        
        $this->prepareMapCommon($query, $databaseQuery, $plan, $withQueryParams);
        $this->method($this->updateMapper, 'map', null, array($databaseQuery, $update), 0);
        
        $this->assertSame($plan, $this->queryMapper->mapUpdate($query, $update));
    }
    
    /**
     * @covers ::mapFind
     * @covers ::<protected>
     */
    public function testMapFind()
    {
        $this->findTest(false, false);
        $this->findTest(true, true);
    }
    
    /**
     * @covers ::mapDelete
     * @covers ::<protected>
     */
    public function testMapDelete()
    {
        $this->deleteTest(false, false);
        $this->deleteTest(true, true);
    }
    
    protected function findTest($withPreload, $withQueryParams = false)
    {
        $query = $this->getQuery();
        
        $preload = null;
        if($withPreload) {
            $preload = $this->getPreload();
        }
        
        $repository = $this->prepareRepository($this->modelName);
        $databaseQuery = $this->prepareDatabaseQuery('select', $repository);
        
        $step  = $this->prepareStep('reusableResult', array($databaseQuery));
        
        $loader = $this->prepareLoader('reusableResult', array($repository, $step));
        
        $loadersOffset = 1;
        
        if($withPreload) {
            $preloadingProxy = $this->prepareLoader('preloadingProxy', array($loader), $loadersOffset++);
            $loader = $preloadingProxy;
        }
        
        $cachingProxy = $this->prepareLoader('cachingProxy', array($loader), $loadersOffset++);
        
        $plan  = $this->preparePlan('loader', array($step, $cachingProxy));
        
        $this->prepareMapCommon($query, $databaseQuery, $plan, $withQueryParams);
        
        if($withPreload) {
            $preloadPlan = $this->getPlan('Steps');
            $this->method($plan, 'preloadPlan', $preloadPlan, array(), 1);
            $this->method($this->preloadMapper, 'map', null, array($preloadingProxy, $this->modelName, $preload, $step), 0);
        }
        
        $this->assertSame($plan, $this->queryMapper->mapFind($query, $preload));
    }
    
    protected function deleteTest($cascade, $withQueryParams = false)
    {
        $query = $this->getQuery();
        $repository = $this->prepareRepository($this->modelName);
        
        $deleteQuery = $this->prepareDatabaseQuery('delete', $repository);
        $step  = $this->prepareStep('query', array($deleteQuery));
        $plan  = $this->preparePlan('query', array($step));
        
        $this->method($this->cascadeDeleteMapper, 'isModelHandled', $cascade, array($this->modelName), 0);
        if($cascade) {
            $selectQuery = $this->prepareDatabaseQuery('select', $repository, 1);
            $this->prepareMapCommon($query, $selectQuery, $plan, $withQueryParams);
            $requiredPlan = $this->getPlan('Steps');
            $this->method($plan, 'requiredPlan', $requiredPlan, array(), 1);
            $this->method($this->cascadeDeleteMapper, 'map', null, array($deleteQuery, $selectQuery, $this->modelName, $requiredPlan), 1);
        }else{
            $this->prepareMapCommon($query, $deleteQuery, $plan, $withQueryParams);
        }
        
        
        $this->assertSame($plan, $this->queryMapper->mapDelete($query));
    }
    
    protected function prepareMapCommon($query, $databaseQuery, $plan, $withParameters = false, $databaseQueryAt = 0)
    {
        $conditions = array('test');
        $this->method($query, 'getConditions', $conditions, array());
        
        $requiredPlan = $this->getPlan('Steps');
        $this->method($plan, 'requiredPlan', $requiredPlan, array(), 0);
        
        $this->method($this->conditionsMapper, 'map', null, array(
            $databaseQuery,
            $this->modelName,
            $conditions,
            $requiredPlan
        ), 0);
        
        if($withParameters) {
            $offset = 5;
            $limit = 1;
            $orderBySets = array(
                array('name', 'desc'),
                array('id', 'asc')
            );
        }else{
            $offset = 0;
            $limit = 0;
            $orderBySets = array();
        }
        
        $this->method($query, 'getOffset', $offset, array());
        if($offset > 0) {
            $this->method($databaseQuery, 'offset', null, array($offset), $databaseQueryAt++);
        }
        
        $this->method($query, 'getLimit', $limit, array());
        if($limit > 0) {
            $this->method($databaseQuery, 'limit', null, array($limit), $databaseQueryAt++);
        }
        
        $orderByValues = array();
        foreach($orderBySets as $set) {
            $orderBy = $this->getOrderBy($set[0], $set[1]);
            $orderByValues[] = $orderBy; 
            
            $method = $set[1] === 'desc' ? 'orderDescendingBy' : 'orderAscendingBy';
            
            $this->method($databaseQuery, $method, null, array($set[0]), $databaseQueryAt++);
        }
        $this->method($query, 'getOrderBy', $orderByValues, array());
    }
    
    protected function prepareDatabaseQuery($type, $repository = null, $at = 0)
    {
        if($repository === null)
            $repository = $this->prepareRepository($this->modelName);
        $query = $this->getDatabaseQuery($type);
        $this->method($repository, 'database'.ucfirst($type).'Query', $query, array(), $at);
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
        $plan = $this->getPlan($this->planClasses[$type]);
        $this->method($this->plans, $type, $plan, $params, 0);
        return $plan;
    }
    
    protected function prepareLoader($type, $params, $at = 0)
    {
        $loader = $this->getLoader($this->loaderClasses[$type]);
        $this->method($this->loaders, $type, $loader, $params, $at);
        return $loader;
    }
    
    protected function prepareRepository($name)
    {
        $repository=$this->getRepository();
        $this->method($this->databaseModel, 'repository', $repository, array($name), 0);
        return $repository;
    }
    
    protected function getQuery()
    {
        $query = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
        $this->method($query, 'modelName', $this->modelName, array());
        return $query;
    }
    
    protected function getOrderBy($field, $direction)
    {
        $orderBy = $this->quickMock('\PHPixie\ORM\Values\OrderBy');
        $this->method($orderBy, 'field', $field, array());
        $this->method($orderBy, 'direction', $direction, array());
        return $orderBy;
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
    
    protected function getLoader($class)
    {
        return $this->abstractMock('\PHPixie\ORM\Loaders\Loader\\'.ucfirst($class));
    }
    
    protected function getPreload()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Preload');
    }
    
    protected function getUpdate()
    {
        return $this->quickMock('\PHPixie\ORM\Values\Update');
    }
}