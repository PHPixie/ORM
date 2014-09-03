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
        $repositories = $this->prepareRepositories();
        $

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
        return $this->abstractMock('\PHPixie\ORM\Repositories\Type\Database');
    }

}
