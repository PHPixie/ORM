<?php

namespace PHPixieTests\ORM\Relationships\Type\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Handler
 */
class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\HandlerTest
{
    protected $configData = array(
        'leftModel'     => 'fairy',
        'leftProperty'  => 'flowers',
        'leftPivotKey'  => 'fairy_id',
        'rightModel'    => 'flower',
        'rightProperty' => 'fairies',
        'rightPivotKey' => 'flower_id',
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
        $related = $this->getModel();
        
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
        $model = $this->getModel();
        $loader = $this->abstractMock('\PHPixie\ORM\Loaders\Loader');
        $editable = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
        
        $this->prepareQuery($side, $query, $model);
        $this->method($query, 'find', $loader, array(), 1);
        $this->method($this->loaders, 'editableProxy', $editable, array($loader), 0);
        
        $this->assertEquals($editable, $this->handler->loadProperty($side, $model));
    }
    
    protected function prepareQuery($side, $query, $related)
    {
        $type = $side->type();
        $opposing = $type === 'left' ? 'right' : 'left';
        
        $data = $this->configData;
        $repository = $this->getRepository();
        $this->method($this->repositories, 'get', $repository, array($data[$type.'Model']), 0);
        $this->method($repository, 'query', $query, array(), 0);
        $this->method($query, 'related', $query, array($data[$type.'Property'], $related), 0);
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
        $this->method($this->plans, 'plan', $m['plan'], array(), 0);
        
        $this->setRepositories(array(
            'fairy' => $m['leftRepo'],
            'flower' => $m['rightRepo'],
        ));
        
        $items = $this->getModel();
        $data = $this->configData;
        $this->method($this->planners, 'pivot', $m['pivotPlanner'], array());
        
        $this->method($m['pivotPlanner'], 'side', $m[$side.'Side'],
                      array($items, $m[$side.'Repo'], $data[$side.'PivotKey']), 0);
        
        $this->method($m['leftRepo'], 'connection', $m['connection'], array(), 0);
        
        $this->method($m['pivotPlanner'], 'pivot', $m['pivotPivot'],
                      array($m['connection'], $data['pivot']), 1);
        
        $this->method($m['pivotPlanner'], 'unlinkAll', array(),
                      array($m['pivotPivot'], $m[$side.'Side'], $m['plan']), 2);
        
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
        $this->method($this->plans, 'plan', $m['plan'], array(), 0);
        
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
            'leftItems' => $this->getModel(),
            'leftSide'  => $this->getPivotSide(),
            'leftRepo'  => $this->getRepository(),
            
            'rightItems' => $this->getModel(),
            'rightSide' => $this->getPivotSide(),
            'rightRepo'  => $this->getRepository(),
            
            'plan' => $this->getPlan(),
            
            'pivotPlanner' => $this->getPivotPlanner(),
            'pivotPivot' => $this->getPivotPivot(),
            
            'connection' => $this->getConnection()
        );
        
        return $m;
    }
    
    /**
     * @covers ::mapQuery
     * @covers ::<protected>
     */
    public function testMapLeftRelationship()
    {
        $this->mapQueryTest('left');
    }
    
    /**
     * @covers ::mapQuery
     * @covers ::<protected>
     */
    public function testMapRightRelationship()
    {
        $this->mapQueryTest('right');
    }
    
    protected function mapQueryTest($type)
    {
        $opposing = $type === 'left' ? 'right' :'left';
        $m = $this->getRelationshipMocks($type, $this->configData);
        $plan = $this->getPlan();
        
        $sideOffset = $m[$type.'Offset'];
        $opposingOffset = $m[$opposing.'Offset'];
        
        $this->method($m[$type.'Repo'], 'idField', $type.'Id', array(), $sideOffset++);
        
        $sideQuery = $this->getDatabaseQuery();
        $this->method($m[$type.'Repo'], 'databaseSelectQuery', $sideQuery, array(), $sideOffset++);
        $this->method($m[$type.'Repo'], 'modelName', $this->configData[$type.'Model'], array(), $sideOffset++);
        $this->method($sideQuery, 'fields', $sideQuery, array(array($type.'Id')), 0);
        
        $this->method($this->groupMapper, 'mapConditions', null, array(
            $sideQuery,
            array(5),
            $this->configData[$type.'Model'],
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
        $this->method($m[$opposing.'Repo'], 'idField', $opposing.'Id', array(), $opposingOffset++);
        $this->method($m['inPlanner'], 'subquery', null, array(
            $query,
            $opposing.'Id',
            $pivotQuery,
            $this->configData[$opposing.'PivotKey'],
            $plan,
            'or',
            true
        ), 1);
        
        $group = $this->getConditionGroup('or', true, array(5));
        
        $this->handler->mapQuery($m['side'], $group, $query, $plan);
        
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
        $this->mapPreloadTest('right');
    }
    
    protected function mapPreloadTest($type)
    {
        $opposing = $type === 'left' ? 'right' :'left';
        $m = $this->getRelationshipMocks($type, $this->configData);
        $sideOffset = $m[$type.'Offset'];
        $opposingOffset = $m[$opposing.'Offset'];
        
        $preloadPlan = $this->getPlan();
        $repoLoader = $this->getReusableResultLoader();
        
        $pivotQuery = $this->getDatabaseQuery();
        $this->method($m['pivotPivot'], 'databaseSelectQuery', $pivotQuery, array(), 0);
        
        
        $resultStep = $this->getReusableResult();
        $this->method($repoLoader, 'resultStep', $resultStep, array(), 0);
        $this->method($m[$opposing.'Repo'], 'idField', $opposing.'Id', array(), $opposingOffset++);
        
        $this->method($m['inPlanner'], 'result', null, array(
            $pivotQuery,
            $this->configData[$opposing.'PivotKey'],
            $resultStep,
            $opposing.'Id',
            $preloadPlan
        ), 0);
        
        $pivotResult = $this->getReusableResult();
        $this->method($this->steps, 'reusableResult', $pivotResult, array($pivotQuery), 0);
        $this->method($preloadPlan, 'add', null, array($pivotResult), 0);
        
        $sideQuery = $this->getDatabaseQuery();
        $this->method($m[$type.'Repo'], 'databaseSelectQuery', $sideQuery, array(), $sideOffset++);
        $this->method($m[$type.'Repo'], 'idField', $type.'Id', array(), $sideOffset++);
        
        $this->method($m['inPlanner'], 'result', null, array(
            $sideQuery,
            $type.'Id',
            $pivotResult,
            $this->configData[$type.'PivotKey'],
            $preloadPlan
        ), 1);
        
        $preloadStep = $this->getReusableResult();
        $this->method($this->steps, 'reusableResult', $preloadStep, array($sideQuery), 1);
        $this->method($preloadPlan, 'add', null, array($preloadStep), 1);
        $loader = $this->getReusableResultLoader();
        $this->method($this->loaders, 'reusableResult', $loader, array($m[$type.'Repo'], $preloadStep), 0);
        
        $preloader = $this->getPreloader();
        $this->method($this->relationship, 'preloader', $preloader, array($m['side'], $loader, $pivotResult), 0);
        
        $this->assertEquals($preloader, $this->handler->mapPreload($m['side'], $repoLoader, $preloadPlan));

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
        $this->handler->unlinkAllProperties($side, $owner['model']);
        
        $owner = $this->getLinkModelMocks($type, true);
        $this->handler->unlinkAllProperties($side, $owner['model']);
        
        
        $owner = $this->getLinkModelMocks($type, true, true);
        
        $items = array(
            $this->getLinkModelMocks($opposing, true, true),
            $this->getLinkModelMocks($opposing),
        );
        
        $itemsModels = array();
        foreach($items as $item)
            $itemsModels[]=$item['model'];
        $this->method($owner['loader'], 'accessedModels', $itemsModels, array(), 0);
        $this->method($owner['loader'], 'removeAll', null, array(), 1);
        $this->method($items[0]['loader'], 'remove', null, array(array($owner['model'])), 0);
        
        $this->handler->unlinkAllProperties($side, $owner['model']);
        
        $owner = $this->getLinkModelMocks($type);
        $this->handler->unlinkAllProperties($side, $owner['model']);
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
                                    $this->arrayColumn($items, 'model')
                                );
        
        $item = $this->getLinkModelMocks('right', true, true);
        
        $this->method($item['property'], 'reset', null, array(), 1);
        
        $this->handler->resetProperties(
                                    $side,
                                    $item['model']
                                );
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
                                    $this->arrayColumn($left, 'model'),
                                    $right['model']
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
                                    $this->arrayColumn($left, 'model'),
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
        $model = $this->getModel();
        $property = null;
        $loader = null;
        
        if($propertyExists) {
            $property = $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Model');
            $this->method($property, 'isLoaded', $propertyLoaded, array());
            
            if($propertyLoaded){
                $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
                $this->method($property, 'value', $loader, array());
            }
        }
        
        $propertyName = $this->configData[$side.'Property'];
        $this->method($model, 'relationshipProperty', $property, array($propertyName), null, true);

        return array(
            'model'    => $model,
            'property' => $property,
            'loader'   => $loader
        );
    }
    
    protected function assertPropertyLoaderMethod($side, $owners, $method, $items)
    {
        $itemsModels = $this->arrayColumn($items, 'model');
        
        foreach($owners as $owner)
            $this->method($owner['loader'], $method, null, array($itemsModels), 0);
    }
    
    protected function getModelProperty()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Property\Model');
    }
    
    protected function getRelationshipMocks($type, $data)
    {
        $m = array(
            'side'  => $this->side($type, $data),
            'leftRepo'  => $this->getRepository(),
            
            'rightRepo'  => $this->getRepository(),
            
            'inPlanner' => $this->getInPlanner(),
            
            'pivotPlanner' => $this->getPivotPlanner(),
            'pivotPivot' => $this->getPivotPivot(),
            
            'connection' => $this->getConnection(),
            
            'leftOffset' => 1,
            'rightOffset' => 0
        );
        
        
        $this->setRepositories(array(
            'fairy' => $m['leftRepo'],
            'flower' => $m['rightRepo'],
        ));
        
        $this->method($this->planners, 'in', $m['inPlanner'], array());
        $this->method($this->planners, 'pivot', $m['pivotPlanner'], array());
        
        $this->method($m['leftRepo'], 'connection', $m['connection'], array(), 0);
        $this->method($m['pivotPlanner'], 'pivot', $m['pivotPivot'], array($m['connection'], $data['pivot']));
        
        return $m;
    }
    
    protected function arrayColumn($array, $column){
        $items = array();
        foreach($array as $row)
            $items[]=$row[$column];
        return $items;
    }

    protected function getPreloader(){
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Preloader');
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
        return $this->quickMock('\PHPixie\ORM\Query');
    }
    
    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Repository\Database');
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
            $this->ormBuilder,
            $this->repositories,
            $this->planners,
            $this->plans,
            $this->steps,
            $this->loaders,
            $this->relationship,
            $this->groupMapper,
            $this->cascadeMapper
        );
    }
}