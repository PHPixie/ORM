<?php

namespace PHPixieTests\ORM\Relationships\Type\OneTo;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Handler
 */
abstract class HandlerTest extends \PHPixieTests\ORM\Relationships\Relationship\HandlerTest
{
    protected $configData = array(
        'ownerModel'       => 'fairy',
        'itemModel'        => 'flower',
        'ownerKey'         => 'fairy_id',
        'ownerProperty'    => 'items',
        'itemProperty'     => 'fairy',
    );

    protected $itemSide;

    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        foreach(array('owner', $this->itemSide) as $type) {
            $side = $this->side($type, $this->configData);
            $query = $this->getQuery();
            $related = $this->getModel();
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
        
        $items = $this->getModel();
        $owner = $this->getModel();
        
        $data = $this->configData;
        $config = $this->config($data);
        
        $repositories = $this->prepareRepositories();
        $planners = $this->getPlanners(array('in', 'update'));
        
        $plan = $this->getPlan();
        $this->method($this->plans, 'plan', $plan, array(), 0);
        
        $ownerQuery = $this->getDatabaseQuery();
        $this->method($repositories['owner'], 'databaseSelectQuery', $ownerQuery, array(), 0);
        $this->prepareAddCollectionQuery($ownerQuery, 'owner', $owner, $plan, $planners['in']);
        
        $updateQuery = $this->getDatabaseQuery('update');
        $this->method($repositories['item'], 'databaseUpdateQuery', $updateQuery, array());
        $this->prepareAddCollectionQuery($updateQuery, 'item', $items, $plan, $planners['in'], null, 1, 2);
        
        $this->method($this->planners, 'update', $planners['update'], array(), 4);
        $this->method($repositories['owner'], 'idField', 'id', array(), 3);
        
        $this->method($planners['update'], 'subquery', null, array(
                                                                $updateQuery,
                                                                array(
                                                                    $data['ownerKey'] => 'id'
                                                                ),
                                                                $ownerQuery,
                                                                $plan
                                                            ), 0);
        $this->assertSame($plan, $this->handler->linkPlan($config, $owner, $items));

    }
    
    protected function prepareAddCollectionQuery($query, $type, $items, $plan, $inPlanner, $queryField = null, $inPlannerOffset = 0, $plannersOffset = 0, $repositoryOffset = 1)
    {
        if($queryField === null)
            $queryField = 'id';
        
        $modelName = $this->configData[$type.'Model'];
        $repository = $this->repositories->get($modelName);
        
        $collection = $this->quickMock('\PHPixie\ORM\Planners\Collection');
        $this->method($repository, 'idField', 'id', array(), $repositoryOffset++);
        $this->method($repository, 'modelName', $modelName, array(), $repositoryOffset++);
        
        $this->method($this->planners, 'collection', $collection, array($modelName, $items), $plannersOffset++);
        $this->method($this->planners, 'in', $inPlanner, array(), $plannersOffset++);
        
        $this->method($inPlanner, 'collection', null, array($query, $queryField, $collection, 'id', $plan), $inPlannerOffset);
    }
    
    public function testMapQuery()
    {
        $repositories = $this->prepareRepositories();
        
        foreach(array('owner', 'item') as $type) {
            $side = $this->side($type, $this->configData);
            $query = $this->getDatabaseQuery();
            $plan = $this->getPlan();     

            $subqueryRepository = $repositories[$type == 'owner' ? 'item' : 'owner'];
            $this->method($repositories['owner'], 'idField', 'id', array(), 0);

            if ($type !== 'owner') {
                $queryField = $this->configData['ownerKey'];
                $subqueryField = 'id';
            } else {
                $queryField = 'id';
                $subqueryField = $this->configData['ownerKey'];
            }
            
            $subquery = $this->getDatabaseQuery();
            
            $subqueryRepoOffset = $type == 'owner' ? 0 : 1;
            
            $this->method($subqueryRepository, 'databaseSelectQuery', $subquery, array(), $subqueryRepoOffset++);

            $group = $group = $this->getConditionGroup('and', true, array(5));

            $modelName = $this->configData[$type.'Model'];

            $this->method($subqueryRepository, 'modelName', $modelName, array(), $subqueryRepoOffset++);

            $this->method($this->groupMapper, 'mapConditions', null, array(
                $subquery,
                array(5),
                $modelName,
                $plan
            ), 0);

            $inPlanner = $this->getPlanner('in');
            $this->method($this->planners, 'in', $inPlanner, array(), 0);
            $this->method($inPlanner, 'subquery', null, array(
                $query,
                $queryField,
                $subquery,
                $subqueryField,
                $plan,
                'and',
                true
            ), 0);

            $this->handler->mapQuery($side, $group, $query, $plan);
        }
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
        $this->method($this->repositories, 'get', $repository, array($data[$type.'Model']), 0);
        $this->method($repository, 'query', $query, array(), 0);
        $this->method($query, 'related', $query, array($data[$type.'Property'], $related), 0);
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Query');
    }

    protected function getRepository()
    {
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database');
    }

}
