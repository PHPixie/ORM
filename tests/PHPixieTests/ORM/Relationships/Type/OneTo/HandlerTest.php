<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Handler
 */
abstract class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\Implementation\HandlerTest
{
    protected $planners = array();
    
    protected $itemSide;
    protected $ownerPropertyName;
    protected $propertyConfig;
    protected $configOnwerProperty;
    public function setUp()
    {
        $this->configData = array(
            'ownerModel'        => 'fairy',
            'itemModel'         => 'flower',
            'ownerKey'          => 'fairy_id',
            'itemOwnerProperty' => 'fairy',
            $this->ownerPropertyName => $this->configOnwerProperty,
        );
        
        $this->planners['in'] = $this->getPlanner('in');
        $this->planners['update'] = $this->getPlanner('update');
        
        foreach($this->planners as $name => $planner) {
            $this->method($this->planners, $name, $planner, array());
        }
        $this->propertyConfig = $this->config($this->configData);
        parent::setUp();
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

        foreach(array('owner', 'item') as $type) {
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

            $subquery = $this->getDatabaseQuery();
            $collectionCondition = $this->getCollectionCondition('and', true, array(5));

            $subqueryRepoOffset = $type == 'owner' ? 1 : 0;
            $this->method($subqueryRepository, 'databaseSelectQuery', $subquery, array(), $subqueryRepoOffset++);

            $modelName = $this->configData[$type.'Model'];
            $this->method($subqueryRepository, 'modelName', $modelName, array(), $subqueryRepoOffset++);

            $this->method($this->mapperMocks['conditions'], 'map', null, array(
                $subquery,
                $modelName,
                array(5),
                $plan
            ), 0);

            $this->method($inPlanner, 'subquery', null, array(
                $query,
                $queryField,
                $subquery,
                $subqueryField,
                $plan,
                'and',
                true
            ), 0);

            $this->handler->mapDatabaseQuery($query, $side, $collectionCondition, $plan);
        }
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

            $this->method($this->planners['in'], 'result', null, array(
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
            
            $this->method($preloadRepository, 'modelName', $this->configData[$normalizedType.'Model'], array(), $preloadRepoOffset);
                        
            $this->method($this->mapperMocks['preload'], 'map', null, array(
                $preloadingProxy,
                $this->configData[$normalizedType.'Model'],
                $preloadProperty['preload'],
                $preloadStep,
                $plan
            ), 0);
            
            $preloader = $this->getPreloader($type);
            $this->method($this->relationship, 'preloader', $preloader, array($side, $cachingProxy), 0);
            
            $this->assertSame($preloader, $this->handler->mapPreload(
                $side,
                $preloadProperty['property'],
                $result,
                $plan
            ));
        }
    }

    protected function prepareLinkPlan($owner, $items, $plansOffset = 0, $ownerRepoOffset = 0, $itemRepoOffset= 0, $plannersOffset = 0)
    {
        $ownerRepository = $this->modelMocks['database']->repository($this->configData['ownerModel']);
        $itemRepository = $this->modelMocks['database']->repository($this->configData['itemModel']);
        $data = $this->configData;
        $config = $this->config($data);

        $plan = $this->getPlan();
        $this->method($this->plans, 'steps', $plan, array(), $plansOffset);

        $ownerQuery = $this->getDatabaseQuery();
        $this->method($ownerRepository, 'databaseSelectQuery', $ownerQuery, array(), $ownerRepoOffset++);
        $this->preparePlanItemsSubquery($ownerQuery, 'owner', $owner, $plan, null, 'and', 0, $plannersOffset, $ownerRepoOffset);

        $updateQuery = $this->getDatabaseQuery('update');
        $this->method($itemRepository, 'databaseUpdateQuery', $updateQuery, array(), $itemRepoOffset++);
        $this->preparePlanItemsSubquery($updateQuery, 'item', $items, $plan, $planners['in'], null, 'and', 1, $plannersOffset + 1, $itemRepoOffset++);

        $this->method($this->planners, 'update', $planners['update'], array(), $plannersOffset + 4);
        
        $this->prepareRepositoryConfig($ownerRepository, array('idField' =>'id'), $ownerRepoOffset + 2);

        $this->method($planners['update'], 'subquery', null, array(
                                                                $updateQuery,
                                                                array(
                                                                    $data['ownerKey'] => 'id'
                                                                ),
                                                                $ownerQuery,
                                                                $plan
                                                            ), 0);
        return $plan;
    }
    
    protected function preparePlanItemsSubquery($query, $type, $items, $plan, $inPlanner, $queryField = null, $logic = 'and', $inPlannerOffset = 0, $plannersOffset = 0, $repositoryOffset = 1)
    {
        if($queryField === null)
            $queryField = 'id';

        $modelName = $this->configData[$type.'Model'];
        $repository = $this->modelMocks['database']->repository($modelName);

        $collection = $this->quickMock('\PHPixie\ORM\Planners\Collection');
        
        $this->prepareRepositoryConfig($repository, array('idField' =>'id'), $repositoryOffset++);
        
        $this->method($this->planners, 'in', $inPlanner, array(), $plannersOffset++);

        $itemsQuery = $this->getQuery();
        $this->method($repository, 'query', $itemsQuery, array(), $repositoryOffset++);
        
        $this->method($itemsQuery, 'in', $itemsQuery, array($items), 0);
        
        $this->method($inPlanner, 'databaseModelQuery', null, array($query, $queryField, $itemsQuery, 'id', $plan, $logic), $inPlannerOffset);
    }

    protected function prepareUnlinkTest($constrainOwners, $owners, $constrainItems, $items, $logic = 'and')
    {
        $this->prepareRepositories();
        $itemRepository = $this->modelMocks['database']->repository($this->configData['itemModel']);
        $plan = $this->getPlan();
        $this->method($this->plans, 'steps', $plan, array(), 0);

        $ownerKey = $this->configData['ownerKey'];
        $updateQuery = $this->getDatabaseQuery('update');

        $this->method($itemRepository, 'databaseUpdateQuery', $updateQuery, array(), 0);

        $this->method($updateQuery, 'set', null, array($ownerKey, null), 0);

        $inPlanner = $this->getPlanner('in');
        $inPlannerOffset = 0;
        $plannersOffset = 0;

        if ($constrainItems) {
            $this->preparePlanItemsSubquery($updateQuery, 'item', $items, $plan, $inPlanner, null, 'and', $inPlannerOffset++, $plannersOffset, 1);
            $plannersOffset+=1;
        }

        if ($constrainOwners) {
            $this->preparePlanItemsSubquery($updateQuery, 'owner', $owners, $plan, $inPlanner, $ownerKey, $logic, $inPlannerOffset++, $plannersOffset, 0);
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
    
    protected function getDatabaseEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    abstract protected function getSingleProperty($type);
    
    abstract protected function getPreloader($type);
}
