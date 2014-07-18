<?php

namespace PHPixieTests\ORM\Relationships\Types\ManyToMany;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Types\ManyToMany\Handler
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
        
        $repository = $this->getRepository();
        $this->method($this->repositories, 'get', $repository, array('fairy'), 0);
        
        $query = $this->getQuery();
        $this->method($repository, 'query', $query, array(), 0);
        
        $related = $this->getModel();
        $this->method($query, 'related', $query, array('flowers', $related), 0);
        $this->assertEquals($query, $this->handler->query($side, $related));
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
     * @covers ::unlinkPlan
     * @covers ::<protected>
     */
    public function testUnlinkAllPlan()
    {
        $m = $this->getLinkMocks();
        $data = $this->configData;
        
        $m['leftItems'] = null;
        $m['leftSide'] = null;
        
        $data['pivotConnection'] = 'forest';
        
        $this->modifyLinkPlanTest($m, 'unlink', $data);
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
        $repoLoader = $this->getRepositoryLoader();
        
        $this->method($m['inPlanner'], 'loader', null, array(
            $pivotQuery,
            $this->configData[$opposing.'PivotKey'],
            $repoLoader,
            $opposing.'Id',
            $plan
        ), 0);
        
        $pivotStep = $this->getReusableResult();
        $this->method($this->steps, 'reusableResult', $pivotStep, array($pivotQuery), 0);
        $this->method($preloadPlan, 'push', null, array($pivotStep), array(), 0);
        
        $sideQuery = $this->getDatabaseQuery();
        $this->method($m[$type.'Repo'], 'databaseSelectQuery', $sideQuery, array(), $sideOffset++);
        $this->method($m[$type.'Repo'], 'idField', $type.'Id', array(), $sideOffset++);
        $this->method($m[$type.'Repo'], 'modelName', $this->configData[$type.'Model'], array(), $sideOffset++);
        
        $this->method($m['inPlanner'], 'result', null, array(
            $sideQuery,
            $this->configData[$type.'PivotKey'],
            $pivotStep,
            $type.'Id',
            $preloadPlan
        ), 1);

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
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Side\Config');
    }
    
    protected function getSide()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Side');
    }
    
    protected function getRelationship()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany');
    }
    
    protected function getHandler()
    {
        return new \PHPixie\ORM\Relationships\Types\ManyToMany\Handler(
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