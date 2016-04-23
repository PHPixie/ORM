<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Handler
 */
abstract class HandlerTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\HandlerTest
{
    protected $plannerMocks = array();
    
    protected $itemSide;
    protected $ownerPropertyName;
    protected $propertyConfig;
    protected $configOwnerProperty;
    public function setUp()
    {
        $this->configData = array(
            'ownerModel'        => 'fairy',
            'itemModel'         => 'flower',
            'ownerKey'          => 'fairyId',
            'itemOwnerProperty' => 'fairy',
            $this->ownerPropertyName => $this->configOwnerProperty,
        );
        
        $this->plannerMocks['in'] = $this->getPlanner('in');
        $this->plannerMocks['update'] = $this->getPlanner('update');
        
        $this->propertyConfig = $this->config($this->configData);
        parent::setUp();
        
        foreach($this->plannerMocks as $name => $planner) {
            $this->method($this->planners, $name, $planner, array());
        }
    }
    
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        foreach(array('owner', $this->itemSide) as $type) {
            $side = $this->side($type, $this->configData);
            $query = $this->getQuery();
            $related = $this->getEntity();
            $this->prepareQuery($side, $query, $related);
            $this->assertEquals($query, $this->handler->query($side, $related));
        }
    }

    /**
     * @covers ::linkPlan
     * @covers ::<protected>
     */
    public function testLinkPlan()
    {   
        $owner = $this->getDatabaseEntity();
        $items = $this->getDatabaseEntity();
        $this->prepareRepositories();
        
        $plan = $this->prepareLinkPlan($owner, $items);
        $this->assertSame($plan, $this->handler->linkPlan($this->propertyConfig, $owner, $items));
    }

    /**
     * @covers ::mapDatabaseQuery
     * @covers ::<protected>
     */
    public function testMapDatabaseQuery()
    {
        $repositories = $this->prepareRepositories();
        
        $this->mapDatabaseQueryTest($repositories, 'owner', true);
        $this->mapDatabaseQueryTest($repositories, 'owner', false);
        
        $this->mapDatabaseQueryTest($repositories, $this->itemSide, true);
        $this->mapDatabaseQueryTest($repositories, $this->itemSide, false);
    }
    
    protected function mapDatabaseQueryTest($repositories, $type, $hasConditions)
    {
        $side = $this->side($type, $this->configData);
        $query = $this->getDatabaseQuery();
        $plan = $this->getPlan();
        
        $subqueryRepository = $repositories[$type == 'owner' ? 'owner' : 'item'];
        $this->prepareRepositoryConfig($repositories['owner'], array('idField' =>'id'));
        
        if ($type === 'owner') {
            $queryField = $this->configData['ownerKey'];
            $subqueryField = 'id';
        } else {
            $queryField = 'id';
            $subqueryField = $this->configData['ownerKey'];
        }
        
        $isOwner = $type === 'owner';
        
        $subquery = $this->getDatabaseQuery();
        $subqueryRepoOffset = $isOwner ? 1 : 0;
        $this->method($subqueryRepository, 'databaseSelectQuery', $subquery, array(), $subqueryRepoOffset++);
        
        $subQueryAt = 0;
        $queryAt = 0;
        
        $conditions = $hasConditions ? array(5) : array();
        $collectionCondition = $this->getCollectionCondition('or', true, $conditions);
        
        
        
        if(!$isOwner) {
            $this->method($subquery, 'whereNot', null, array($this->configData['ownerKey'], null), $subQueryAt++);
        }
        
        if($hasConditions) {
            
            if(!$isOwner) {
                $this->method($subquery, 'startGroup', null, array(), $subQueryAt++);
            }   
            
            $modelName = $type === 'owner' ? $this->configData['ownerModel'] : $this->configData['itemModel'];
            $this->method($subqueryRepository, 'modelName', $modelName, array(), $subqueryRepoOffset++);
            $this->method($this->mapperMocks['conditions'], 'map', null, array(
                $subquery,
                $modelName,
                array(5),
                $plan
            ), 0);
            
            if(!$isOwner) {
                $this->method($subquery, 'endGroup', null, array(), $subQueryAt++);
            }   
        }
        
        if($isOwner) {
            $this->method($query, 'startConditionGroup', null, array('or', true), $queryAt++);
            $this->method($query, 'whereNot', null, array($this->configData['ownerKey'], null), $queryAt++);
            
            $subqueryLogic  = 'and';
            $negatesubQuery = false;
        }else{
            $subqueryLogic  = 'or';
            $negatesubQuery = true;
        }
        
        $this->method($this->plannerMocks['in'], 'subquery', null, array(
            $query,
            $queryField,
            $subquery,
            $subqueryField,
            $plan,
            $subqueryLogic,
            $negatesubQuery
        ), 0);
        
        if($isOwner) {
            $this->method($query, 'endGroup', null, array(), $queryAt++);
        }
        
        $this->handler->mapDatabaseQuery($query, $side, $collectionCondition, $plan);
    }
    
    /**
     * @covers ::mapPreload
     * @covers ::<protected>
     */
    public function testMapPreload()
    {
        $repositories = $this->prepareRepositories();

        foreach(array('owner', $this->itemSide) as $type) {
            $side = $this->side($type, $this->configData);
            $query = $this->getDatabaseQuery();
            $preloadProperty = $this->preloadPropertyValue();
            $result = $this->getReusableResult();
            $plan = $this->getPlan();
            $relatedLoader = $this->getLoader();

            $normalizedType = $type == 'owner' ? 'owner' : 'item';
            $preloadRepository = $repositories[$normalizedType];
            $this->prepareRepositoryConfig($repositories['owner'], array('idField' =>'id'));
            
            if ($type === 'owner') {
                $queryField = 'id';
                $resultField = $this->configData['ownerKey'];
            } else {
                $queryField = $this->configData['ownerKey'];
                $resultField = 'id';
            }

            $query = $this->getDatabaseQuery();

            $preloadRepoOffset = $type == 'owner' ? 1 : 0;
            $this->method($preloadRepository, 'databaseSelectQuery', $query, array(), $preloadRepoOffset++);

            $this->method($this->plannerMocks['in'], 'result', null, array(
                $query,
                $queryField,
                $result,
                $resultField,
                $plan
            ), 0);

            $preloadStep = $this->getReusableResult();
            $this->method($this->steps, 'reusableResult', $preloadStep, array($query), 0);
            $this->method($plan, 'add', null, array($preloadStep), 0);
            $loader = $this->getReusableResultLoader();
            
            $this->method($this->loaders, 'reusableResult', $loader, array($preloadRepository, $preloadStep), 0);
            
            $preloadingProxy = $this->getLoaderProxy('preloading');
            $this->method($this->loaders, 'preloadingProxy', $preloadingProxy, array($loader), 1);
            
            $cachingProxy = $this->getLoaderProxy('caching');
            $this->method($this->loaders, 'cachingProxy', $cachingProxy, array($preloadingProxy), 2);
            
            $this->method($preloadRepository, 'modelName', $this->configData[$normalizedType.'Model'], array(), $preloadRepoOffset++);
                        
            $this->method($this->mapperMocks['preload'], 'map', null, array(
                $preloadingProxy,
                $this->configData[$normalizedType.'Model'],
                $preloadProperty['preload'],
                $preloadStep,
                $plan,
                $cachingProxy
            ), 0);
            
            $config = $this->prepareRepositoryConfig($preloadRepository, array(), $preloadRepoOffset);
            $preloader = $this->getPreloader($type);
            $this->method($this->relationship, 'preloader', $preloader, array(
                $side,
                $config,
                $preloadStep,
                $cachingProxy
            ), 0);
            
            $this->assertSame($preloader, $this->handler->mapPreload(
                $side,
                $preloadProperty['property'],
                $result,
                $plan,
                $relatedLoader
            ));
        }
    }

    /**
     * @covers ::handleDelete
     * @covers ::<protected>
     */
    public function testHandleDelete()
    {
        $this->handleDeleteTest('update');
        $this->handleDeleteTest('delete');
        $this->handleDeleteTest('delete', true);
    }
    
    protected function handleDeleteTest($onDelete = 'update', $isItemModelHandled = false)
    {
        $data = $this->configData;
        $data['onDelete'] = $onDelete;
        $side = $this->side($this->ownerPropertyName, $data);
        
        $result = $this->getReusableResult();
        $plan = $this->getPlan();
        $sidePath = $this->getCascadePath();
           
        $repositories = $this->prepareRepositories();
        
        $ownerRepository = $this->modelMocks['database']->repository($data['ownerModel']);
        $itemRepository = $this->modelMocks['database']->repository($data['itemModel']);
        $itemRepositoryAt = 0;
        
        $hasHandledSides = false;
        
        if($onDelete === 'update') {
            $query = $this->getDatabaseQuery('update');
            $this->method($itemRepository, 'databaseUpdateQuery', $query, array(), $itemRepositoryAt++);
        }else{
            $this->method(
                $this->mapperMocks['cascadeDelete'],
                'isModelHandled',
                $isItemModelHandled,
                array($data['itemModel']),
                0
            );
            $hasHandledSides = $isItemModelHandled;
            
            if($hasHandledSides) {
                $query = $this->getDatabaseQuery('select');
                $this->method($itemRepository, 'databaseSelectQuery', $query, array(), $itemRepositoryAt++);
                
            }else{
                $query = $this->getDatabaseQuery('delete');
                $this->method($itemRepository, 'databaseDeleteQuery', $query, array(), $itemRepositoryAt++);
            }
        }
        $this->prepareRepositoryConfig($ownerRepository, array('idField' =>'ownerId'), 0);
        
        $this->method($this->plannerMocks['in'], 'result', null, array(
            $query,
            $data['ownerKey'],
            $result,
            'ownerId',
            $plan
        ), 0);
        
        if($hasHandledSides) {
            $this->method($this->mapperMocks['cascadeDelete'], 'handleQuery', null, array(
                $query,
                $data['itemModel'],
                $plan,
                $sidePath
            ), 1);
        }else{
            $step = $this->getQueryStep();
            $this->method($this->steps, 'query', $step, array($query), 0);
            $this->method($plan, 'add', null, array($step), 0);
        }
        
        $this->handler->handleDelete($side, $result, $plan, $sidePath);
        
    }
        
    protected function prepareLinkPlan($owner, $items, $plansOffset = 0, $stepsOffset = 0, $inPlannerOffset = 0, $ownerRepoOffset = 0, $itemRepoOffset= 0)
    {
        $ownerRepository = $this->modelMocks['database']->repository($this->configData['ownerModel']);
        $itemRepository = $this->modelMocks['database']->repository($this->configData['itemModel']);
        $data = $this->configData;
        $config = $this->config($data);

        $ownerQuery = $this->getDatabaseQuery();
        $this->method($ownerRepository, 'databaseSelectQuery', $ownerQuery, array(), $ownerRepoOffset++);

        $updateQuery = $this->getDatabaseQuery('update');
        $this->method($itemRepository, 'databaseUpdateQuery', $updateQuery, array(), $itemRepoOffset++);
        
        $queryStep = $this->getQueryStep();
        $this->method($this->steps, 'query', $queryStep, array($updateQuery), $stepsOffset);
        
        $plan = $this->getQueryPlan();
        $this->method($this->plans, 'query', $plan, array($queryStep), $plansOffset);
        
        $requiredPlan = $this->getPlan();
        $this->method($plan, 'requiredPlan', $requiredPlan, array(), 0);
        
        $this->method($this->plannerMocks['in'], 'items', null, array(
            $ownerQuery,
            $this->configData['ownerModel'],
            $owner,
            $requiredPlan
        ), $inPlannerOffset++);
        
        $this->method($this->plannerMocks['in'], 'items', null, array(
            $updateQuery,
            $this->configData['itemModel'],
            $items,
            $requiredPlan
        ), $inPlannerOffset++);

        $this->prepareRepositoryConfig($ownerRepository, array('idField' =>'id'), $ownerRepoOffset++);

        $this->method($this->plannerMocks['update'], 'subquery', null, array(
                                                                $updateQuery,
                                                                array(
                                                                    $data['ownerKey'] => 'id'
                                                                ),
                                                                $ownerQuery,
                                                                $requiredPlan
                                                            ), 0);
        return $plan;
    }

    protected function prepareUnlinkTest($constrainOwners, $owners, $constrainItems, $items, $logic = 'and')
    {
        $this->prepareRepositories();
        
        $itemRepository = $this->modelMocks['database']->repository($this->configData['itemModel']);
        $updateQuery = $this->getDatabaseQuery('update');
        $this->method($itemRepository, 'databaseUpdateQuery', $updateQuery, array(), 0);
        
        $queryStep = $this->getQueryStep();
        $this->method($this->steps, 'query', $queryStep, array($updateQuery), 0);
        
        $plan = $this->getQueryPlan();
        $this->method($this->plans, 'query', $plan, array($queryStep), 0);
        
        $requiredPlan = $this->getPlan();
        $this->method($plan, 'requiredPlan', $requiredPlan, array(), 0);

        $ownerKey = $this->configData['ownerKey'];
        
        $this->method($updateQuery, 'set', null, array($ownerKey, null), 0);
        
        $inPlannerAt = 0;
        
        if ($constrainItems) {
            $this->method($this->plannerMocks['in'], 'items', null, array(
                $updateQuery,
                $this->configData['itemModel'],
                $items,
                $requiredPlan
            ), $inPlannerAt++);
        }

        if ($constrainOwners) {
            $this->method($this->plannerMocks['in'], 'itemIds', null, array(
                $updateQuery,
                $this->configData['ownerKey'],
                $this->modelMocks['database']->repository($this->configData['ownerModel']),
                $owners,
                $requiredPlan,
                $logic
            ), $inPlannerAt++);
        }

        return $plan;
    }


    protected function prepareRepositories()
    {
        $repositories = array(
            'owner' => $this->getRepository(),
            'item'  => $this->getRepository(),
        );

        $this->setRepositories(array(
            $this->configData['ownerModel'] => $repositories['owner'],
            $this->configData['itemModel']  => $repositories['item'],
        ));

        return $repositories;
    }

    protected function prepareQuery($side, $query, $related)
    {
        $type = $side->type();
        $data = $this->configData;
        if($type !== 'owner')
            $type = 'item';

        $repository = $this->getRepository();
        $this->method($this->modelMocks['database'], 'repository', $repository, array($data[$type.'Model']), 0);
        $this->method($repository, 'query', $query, array(), 0);
        $this->method($query, 'relatedTo', $query, array($this->sidePropertyName($type), $related));
    }
    
    protected function prepareLoadSingleProperty($side, $related, $value)
    {
        $query = $this->getQuery();
        
        $this->prepareQuery($side, $query, $related);
        $this->method($query, 'findOne', $value, array());
        return $value;
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
    }

    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }
    
    protected function addSingleProperty($entity, $type, $propertyExists = true, $loaded = false, $value = null, $expectCreateMissing = null)
    {
        $property = null;
        if($propertyExists) {
            $property = $this->getSingleProperty($type);
            $this->method($property, 'isLoaded', $loaded, array());
        
            if($loaded) {
                $this->method($property, 'value', $value, array());
            }
        }
        $propertyName = $this->opposingPropertyName($type);
        
        $with = array($propertyName);
        if($expectCreateMissing !== null)
            $with[]=$expectCreateMissing;
        
        $this->method($entity, 'getRelationshipProperty', $property, $with, null, true);
        return array(
            'entity'   => $entity,
            'property' => $property,
            'value'    => $value
        );
    }
    
    protected function expectSetValue($entityMock, $valueMock = null)
    {
        $value = null;
        if($valueMock !== null)
            $value = $valueMock['entity'];
        
        $entityMock['property']
            ->expects($this->once())
            ->method('setValue')
            ->with($this->identicalTo($value));
    }
    
    protected function expectsExactly($mock, $method, $exactly)
    {
        $mock
            ->expects($this->exactly($exactly))
            ->method($method)
            ->with();
    }
    
    protected function config($map, $methodMap = array())
    {
        $methodMap['ownerProperty'] = $this->configData[$this->ownerPropertyName];
        return parent::config($map, $methodMap);
    }
    
    protected function sidePropertyName($type)
    {
        if($type === 'owner')
            return $this->configData[$this->ownerPropertyName];
        
        return $this->configData['itemOwnerProperty']; 
    }
    
    protected function opposingPropertyName($type)
    {
        $opposing = $type == 'owner' ? $this->itemSide : 'owner';
        return $this->sidePropertyName($opposing);
    }

    protected function getQueryStep()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Step\Query');
    }
    
    protected function getQueryPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Query');
    }
    
    protected function getDatabaseEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    abstract protected function getSingleProperty($type);
    
    abstract protected function getPreloader($type);
}
