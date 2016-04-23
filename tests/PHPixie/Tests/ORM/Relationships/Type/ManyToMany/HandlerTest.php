<?php

namespace PHPixie\Tests\ORM\Relationships\Type\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Handler
 */
class HandlerTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\HandlerTest
{
    protected $configData = array(
        'leftModel'     => 'fairy',
        'leftProperty'  => 'flowers',
        'leftPivotKey'  => 'fairyId',
        'rightModel'    => 'flower',
        'rightProperty' => 'fairies',
        'rightPivotKey' => 'flowerId',
        'pivot'         => 'fairies_flowers'
    );

    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $side = $this->side('left', $this->configData);
        $query = $this->getQuery();
        $related = $this->getDatabaseEntity();

        $this->prepareQuery($side, $query, $related);
        $this->assertEquals($query, $this->handler->query($side, $related));
    }

    /**
     * @covers ::loadProperty
     * @covers ::<protected>
     */
    public function testLoadProperty()
    {
        $side = $this->side('left', $this->configData);
        $query = $this->getQuery();
        $entity = $this->getDatabaseEntity();
        $loader = $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
        $editable = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');

        $this->prepareQuery($side, $query, $entity);
        $this->method($query, 'find', $loader, array(), 1);
        $this->method($this->loaders, 'editableProxy', $editable, array($loader), 0);
        
        $property = $this->getProperty();
        $this->method($entity, 'getRelationshipProperty', $property, array($this->configData['rightProperty']), 0, true);
        $this->method($property, 'setValue', null, array($editable), 0);
        
        $this->handler->loadProperty($side, $entity);
    }

    protected function prepareQuery($side, $query, $related)
    {
        $type = $side->type();
        $opposing = $type === 'left' ? 'right' : 'left';

        $data = $this->configData;
        $repository = $this->getRepository();
        $this->method($this->modelMocks['database'], 'repository', $repository, array($data[$type.'Model']), 0);
        $this->method($repository, 'query', $query, array(), 0);
        $this->method($query, 'relatedTo', $query, array($data[$type.'Property'], $related), 0);
    }

    /**
     * @covers ::linkPlan
     * @covers ::<protected>
     */
    public function testLinkPlan()
    {
        $m = $this->getLinkMocks();
        $this->modifyLinkPlanTest($m, 'link', $this->configData);
    }

    /**
     * @covers ::unlinkPlan
     * @covers ::<protected>
     */
    public function testUnlinkPlan()
    {
        $m = $this->getLinkMocks();
        $this->modifyLinkPlanTest($m, 'unlink', $this->configData);
    }

    /**
     * @covers ::unlinkAllPlan
     * @covers ::<protected>
     */
    public function testUnlinkAllPlanLeft()
    {
        $this->unlinkAllPlanTest('left');
    }

    /**
     * @covers ::unlinkAllPlan
     * @covers ::<protected>
     */
    public function testUnlinkAllPlanRight()
    {
        $this->unlinkAllPlanTest('right');
    }

    protected function unlinkAllPlanTest($side)
    {
        $m = $this->getLinkMocks();
        $this->method($this->plans, 'steps', $m['plan'], array(), 0);

        $this->setRepositories(array(
            'fairy' => $m['leftRepo'],
            'flower' => $m['rightRepo'],
        ));
        
        $opposing = $side === 'left' ? 'right' :'left';
        
        $items = $this->getDatabaseEntity();
        $data = $this->configData;
        $this->method($this->planners, 'pivot', $m['pivotPlanner'], array());

        $this->method($m['pivotPlanner'], 'side', $m[$opposing.'Side'],
                      array($items, $m[$opposing.'Repo'], $data[$opposing.'PivotKey']), 0);

        $this->method($m['leftRepo'], 'connection', $m['connection'], array(), 0);

        $this->method($m['pivotPlanner'], 'pivot', $m['pivotPivot'],
                      array($m['connection'], $data['pivot']), 1);

        $this->method($m['pivotPlanner'], 'unlinkAll', array(),
                      array($m['pivotPivot'], $m[$opposing.'Side'], $m['plan']), 2);

        $side = $this->side($side, $this->configData);
        $this->assertSame($m['plan'], $this->handler->unlinkAllPlan($side, $items));
    }

    protected function modifyLinkPlanTest($m, $type, $data)
    {
        $config = $this->config($data);

        $this->setRepositories(array(
            'fairy' => $m['leftRepo'],
            'flower' => $m['rightRepo'],
        ));

        $this->method($this->planners, 'pivot', $m['pivotPlanner'], array());
        $this->method($this->plans, 'steps', $m['plan'], array(), 0);

        $plannerAt = 0;
        if($m['leftSide'] !== null) {
            $this->method($m['pivotPlanner'], 'side', $m['leftSide'],
                      array($m['leftItems'], $m['leftRepo'], $data['leftPivotKey']), $plannerAt++);
        }

        $this->method($m['pivotPlanner'], 'side', $m['rightSide'],
                      array($m['rightItems'], $m['rightRepo'], $data['rightPivotKey']), $plannerAt++);

        if(array_key_exists('pivotConnection', $data)) {
            $this->method($this->ormBuilder, 'databaseConnection',
                          $m['connection'], array($data['pivotConnection']), 0);
        }else{
            $this->method($m['leftRepo'], 'connection', $m['connection'], array(), 0);
        }

        $this->method($m['pivotPlanner'], 'pivot', $m['pivotPivot'],
                      array($m['connection'], $data['pivot']), $plannerAt++);

        $this->method($m['pivotPlanner'], $type, null,
                      array($m['pivotPivot'], $m['leftSide'], $m['rightSide'], $m['plan']), $plannerAt++);
        $method = $type.'Plan';
        $this->assertEquals($m['plan'], $this->handler->$method($config, $m['leftItems'], $m['rightItems']));

    }

    protected function getLinkMocks()
    {
        $m = array(
            'leftItems' => $this->getDatabaseEntity(),
            'leftSide'  => $this->getPivotSide(),
            'leftRepo'  => $this->getRepository(),

            'rightItems' => $this->getDatabaseEntity(),
            'rightSide'  => $this->getPivotSide(),
            'rightRepo'  => $this->getRepository(),

            'plan' => $this->getPlan(),

            'pivotPlanner' => $this->getPivotPlanner(),
            'pivotPivot' => $this->getPivotPivot(),

            'connection' => $this->getConnection()
        );

        return $m;
    }

    /**
     * @covers ::mapDatabaseQuery
     * @covers ::<protected>
     */
    public function testMapLeftDatabaseQuery()
    {
        $this->mapDatabaseQueryTest('left');
    }

    /**
     * @covers ::mapDatabaseQuery
     * @covers ::<protected>
     */
    public function testMapRightDatabaseQuery()
    {
        $this->mapDatabaseQueryTest('right', true);
    }

    protected function mapDatabaseQueryTest($type, $withPivotConnection = false)
    {
        $opposing = $type === 'left' ? 'right' :'left';
        $m = $this->getRelationshipMocks($type, $this->configData, $withPivotConnection);
        $plan = $this->getPlan();

        $sideOffset = $m[$type.'Offset'];
        $opposingOffset = $m[$opposing.'Offset'];

        $this->prepareRepositoryConfig($m[$type.'Repo'], array('idField' => $type.'Id'), $sideOffset++);

        $sideQuery = $this->getDatabaseQuery();
        $this->method($m[$type.'Repo'], 'databaseSelectQuery', $sideQuery, array(), $sideOffset++);
        $this->method($m[$type.'Repo'], 'modelName', $this->configData[$type.'Model'], array(), $sideOffset++);

        $this->method($this->mapperMocks['conditions'], 'map', null, array(
            $sideQuery,
            $this->configData[$type.'Model'],
            array(5),
            $plan
        ), 0);

        $pivotQuery = $this->getDatabaseQuery();
        $this->method($m['pivotPivot'], 'databaseSelectQuery', $pivotQuery, array(), 0);

        $this->method($m['inPlanner'], 'subquery', null, array(
            $pivotQuery,
            $this->configData[$type.'PivotKey'],
            $sideQuery,
            $type.'Id',
            $plan
        ), 0);

        $query = $this->getDatabaseQuery();
        $this->prepareRepositoryConfig($m[$opposing.'Repo'], array('idField' => $opposing.'Id'), $opposingOffset++);
        $this->method($m['inPlanner'], 'subquery', null, array(
            $query,
            $opposing.'Id',
            $pivotQuery,
            $this->configData[$opposing.'PivotKey'],
            $plan,
            'or',
            true
        ), 1);

        $collectionCondition = $this->getCollectionCondition('or', true, array(5));

        $this->handler->mapDatabaseQuery($query, $m['side'], $collectionCondition, $plan);

    }

    /**
     * @covers ::mapPreload
     * @covers ::<protected>
     */
    public function testMapPreloadLeft()
    {
        $this->mapPreloadTest('left');
    }

    /**
     * @covers ::mapPreload
     * @covers ::<protected>
     */
    public function testMapPreloadRight()
    {
        $this->mapPreloadTest('right', true);
    }

    protected function mapPreloadTest($type, $withPivotConnection = false)
    {
        $preloadProperty = $this->preloadPropertyValue();
        $relatedLoader = $this->getLoader();

        $opposing = $type === 'left' ? 'right' :'left';
        $m = $this->getRelationshipMocks($type, $this->configData, $withPivotConnection);
        $sideOffset = $m[$type.'Offset'];
        $opposingOffset = $m[$opposing.'Offset'];

        $plan = $this->getPlan();
        $result = $this->getReusableResult();

        $pivotQuery = $this->getDatabaseQuery();
        $this->method($m['pivotPivot'], 'databaseSelectQuery', $pivotQuery, array(), 0);


        $this->prepareRepositoryConfig($m[$opposing.'Repo'], array('idField' => $opposing.'Id'), $opposingOffset++);

        $this->method($m['inPlanner'], 'result', null, array(
            $pivotQuery,
            $this->configData[$opposing.'PivotKey'],
            $result,
            $opposing.'Id',
            $plan
        ), 0);

        $pivotResult = $this->getReusableResult();
        $this->method($this->steps, 'reusableResult', $pivotResult, array($pivotQuery), 0);
        $this->method($plan, 'add', null, array($pivotResult), 0);

        $sideQuery = $this->getDatabaseQuery();
        $this->method($m[$type.'Repo'], 'databaseSelectQuery', $sideQuery, array(), $sideOffset++);
        $this->prepareRepositoryConfig($m[$type.'Repo'], array('idField' => $type.'Id'), $sideOffset++);

        $this->method($m['inPlanner'], 'result', null, array(
            $sideQuery,
            $type.'Id',
            $pivotResult,
            $this->configData[$type.'PivotKey'],
            $plan
        ), 1);

        $preloadStep = $this->getReusableResult();
        $this->method($this->steps, 'reusableResult', $preloadStep, array($sideQuery), 1);
        $this->method($plan, 'add', null, array($preloadStep), 1);
        $loader = $this->getReusableResultLoader();
        $this->method($this->loaders, 'reusableResult', $loader, array($m[$type.'Repo'], $preloadStep), 0);

        $preloadingProxy = $this->getLoaderProxy('preloading');
        $this->method($this->loaders, 'preloadingProxy', $preloadingProxy, array($loader), 1);

        $cachingProxy = $this->getLoaderProxy('preloading');
        $this->method($this->loaders, 'cachingProxy', $cachingProxy, array($preloadingProxy), 2);

        $this->method($m[$type.'Repo'], 'modelName', $this->configData[$type.'Model'], array(), $sideOffset++);
        $this->method($this->mapperMocks['preload'], 'map', null, array(
            $preloadingProxy,
            $this->configData[$type.'Model'],
            $preloadProperty['preload'],
            $preloadStep,
            $plan,
            $cachingProxy
        ), 0);

        $sideConfig = $this->prepareRepositoryConfig($m[$type.'Repo'], array(), $sideOffset++);
        $preloader = $this->getPreloader();
        $this->method($this->relationship, 'preloader', $preloader, array(
            $m['side'],
            $sideConfig,
            $preloadStep,
            $cachingProxy,
            $pivotResult
        ), 0);

        $this->assertEquals($preloader, $this->handler->mapPreload($m['side'], $preloadProperty['property'], $result, $plan, $relatedLoader));

    }

    /**
     * @covers ::unlinkAllProperties
     * @covers ::<protected>
     */
    public function testUnlinkAllProperties()
    {
        $type = 'left';
        $opposing = $type === 'left' ? 'right' : 'left';

        $side = $this->side($type, $this->configData);
        $this->method($side, 'propertyName', $this->configData[$type.'Property'], array());

        $owner = $this->getLinkModelMocks($type);
        $this->handler->unlinkAllProperties($side, $owner['entity']);

        $owner = $this->getLinkModelMocks($type, true);
        $this->handler->unlinkAllProperties($side, $owner['entity']);


        $owner = $this->getLinkModelMocks($type, true, true);

        $items = array(
            $this->getLinkModelMocks($type, true, true),
            $this->getLinkModelMocks($type),
        );

        $itemsModels = array();
        foreach($items as $item)
            $itemsModels[]=$item['entity'];
        $this->method($owner['loader'], 'accessedEntities', $itemsModels, array(), 0);
        $this->method($owner['loader'], 'removeAll', null, array(), 1);
        $this->method($items[0]['loader'], 'remove', null, array(array($owner['entity'])), 0);

        $this->handler->unlinkAllProperties($side, $owner['entity']);

        $owner = $this->getLinkModelMocks($type);
        $this->handler->unlinkAllProperties($side, $owner['entity']);
    }

    /**
     * @covers ::linkProperties
     * @covers ::unlinkProperties
     * @covers ::<protected>
     */
    public function testModifyLinkProperties()
    {
        $this->modifyPropertyLinkTest('link');
        $this->modifyPropertyLinkTest('unlink');
        $this->resetOwnersTest();
    }

    /**
     * @covers ::resetProperties
     * @covers ::<protected>
     */
    public function testResetProperties()
    {
        $side = $this->side('left', $this->configData);

        $items = array(
            $this->getLinkModelMocks('right', true, true),
            $this->getLinkModelMocks('right', true),
            $this->getLinkModelMocks('right'),
        );

        $this->method($items[0]['property'], 'reset', null, array(), 1);

        $this->handler->resetProperties(
                                    $side,
                                    $this->arrayColumn($items, 'entity')
                                );

        $item = $this->getLinkModelMocks('right', true, true);

        $this->method($item['property'], 'reset', null, array(), 1);

        $this->handler->resetProperties(
                                    $side,
                                    $item['entity']
                                );
    }
    
    /**
     * @covers ::handleDelete
     * @covers ::<protected>
     */
    public function testHandleDeleteLeft()
    {
        $this->handleDeleteTest('left');
    }
    
    /**
     * @covers ::handleDelete
     * @covers ::<protected>
     */
    public function testHandleDeleteRight()
    {
        $this->handleDeleteTest('right');
    }

    
    protected function handleDeleteTest($type)
    {
        $opposing = $type == 'left' ? 'right' : 'left';
        
        $side = $this->side($type, $this->configData, array(
            'modelName' => $this->configData[$opposing.'Model']
        ));
        
        $result = $this->getReusableResult();
        $plan = $this->getPlan();
        $sidePath = $this->getCascadePath();
        
        $m = $this->getRelationshipMocks($type, $this->configData);
        $this->method($this->planners, 'pivot', $m['pivotPlanner'], array());
        
        $opposingAt = $m[$opposing.'Offset'];
        
        $query = $this->getDatabaseQuery('delete');
        $this->method($m['pivotPivot'], 'databaseDeleteQuery', $query, array(), 0);
        
        $this->prepareRepositoryConfig($m[$opposing.'Repo'], array('idField' => $opposing.'Id'), $opposingAt++);
        $this->method($m['inPlanner'], 'result', array(),
                      array($query, $this->configData[$opposing.'PivotKey'], $result, $opposing.'Id', $plan), 0);
        
        $step = $this->getQueryStep();
        $this->method($this->steps, 'query', $step, array($query), 0);
        $this->method($plan, 'add', null, array($step), 0);
        
        $this->handler->handleDelete($side, $result, $plan, $sidePath);
    }

    protected function modifyPropertyLinkTest($method)
    {
        $config = $this->config($this->configData);

        $left = array(
            $this->getLinkModelMocks('left', true, true),
            $this->getLinkModelMocks('left', true, true),
        );

        $right = $this->getLinkModelMocks('right', true, true);
        $action = $method == 'link' ? 'add' : 'remove';
        $this->assertPropertyLoaderMethod('left', $left, $action, array($right));
        $this->assertPropertyLoaderMethod('right', array($right), $action, $left);

        $method = $method.'Properties';
        $this->handler->$method(
                                    $config,
                                    $this->arrayColumn($left, 'entity'),
                                    $right['entity']
                                );
    }

    protected function resetOwnersTest()
    {
        $config = $this->config($this->configData);

        $left = array(
            $this->getLinkModelMocks('left', true, true),
            $this->getLinkModelMocks('left', true, true),
        );

        $right = $this->getQuery();

        foreach($left as $owner)
            $this->method($owner['property'], 'reset', null, array(), 1);

        $this->handler->linkProperties(
                                    $config,
                                    $this->arrayColumn($left, 'entity'),
                                    $right
                                );
    }

    protected function modifyLinkForSingleItemsTest($action)
    {
        $config = $this->config($this->configData);

        $left = $this->getLinkModelMocks('left', true, true);
        $right = $this->getLinkModelMocks('right', true, true);
        $this->assertPropertyLoaderMethod('left', array($left), $action, array($right));
        $this->assertPropertyLoaderMethod('right', array($right), $action, array($left));

        $this->handler->linkProperties($config, $left, $right);
    }

    protected function getLinkModelMocks($side, $propertyExists = false, $propertyLoaded = false)
    {
        $entity = $this->getDatabaseEntity();
        $property = null;
        $loader = null;

        if($propertyExists) {
            $property = $this->getProperty();
            $this->method($property, 'isLoaded', $propertyLoaded, array());

            if($propertyLoaded){
                $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
                $this->method($property, 'value', $loader, array());
            }
        }

        $propertyName = $this->configData[$side.'Property'];
        $this->method($entity, 'getRelationshipProperty', $property, array($propertyName), null, true);

        return array(
            'entity'    => $entity,
            'property' => $property,
            'loader'   => $loader
        );
    }

    protected function assertPropertyLoaderMethod($side, $owners, $method, $items)
    {
        $itemsModels = $this->arrayColumn($items, 'entity');

        foreach($owners as $owner)
            $this->method($owner['loader'], $method, null, array($itemsModels), 0);
    }

    protected function getRelationshipMocks($type, $data, $withPivotConnection = false)
    {
        if($withPivotConnection) {
            $data['pivotConnection'] = 'second';
        }
        
        $m = array(
            'side'  => $this->side($type, $data),
            'leftRepo'  => $this->getRepository(),

            'rightRepo'  => $this->getRepository(),

            'inPlanner' => $this->getInPlanner(),

            'pivotPlanner' => $this->getPivotPlanner(),
            'pivotPivot' => $this->getPivotPivot(),

            'connection' => $this->getConnection(),

            'leftOffset' => $withPivotConnection ? 0 : 1,
            'rightOffset' => 0
        );


        $this->setRepositories(array(
            'fairy' => $m['leftRepo'],
            'flower' => $m['rightRepo'],
        ));

        $this->method($this->planners, 'in', $m['inPlanner'], array());
        $this->method($this->planners, 'pivot', $m['pivotPlanner'], array());
        
        if($withPivotConnection) {
            $this->method(
                $m['pivotPlanner'],
                'pivotByConnectionName',
                $m['pivotPivot'],
                array('second', $data['pivot'])
            );
        }else{
            $this->method($m['leftRepo'], 'connection', $m['connection'], array(), 0);
            $this->method(
                $m['pivotPlanner'],
                'pivot',
                $m['pivotPivot'],
                array($m['connection'], $data['pivot'])
            );
        }

        return $m;
    }

    protected function arrayColumn($array, $column){
        $items = array();
        foreach($array as $row)
            $items[]=$row[$column];
        return $items;
    }

    protected function getDatabaseEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }

    protected function getPreloader()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader');
    }
    
    protected function getProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Entity');
    }

    protected function getConnection()
    {
        return $this->quickMock('\PHPixie\Database\Connection');
    }

    protected function getPivotPivot()
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Pivot');
    }

    protected function getPivotSide()
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot\Side');
    }

    protected function getPivotPlanner()
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Planner\Pivot');
    }

    protected function getInPlanner()
    {
        return $this->quickMock('\PHPixie\ORM\Planners\Planner\In');
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Query');
    }

    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Repository');
    }

    protected function getConfig()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config');
    }

    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side');
    }

    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany');
    }

    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Handler(
            $this->models,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->mappers,
            $this->relationship
        );
    }
}
