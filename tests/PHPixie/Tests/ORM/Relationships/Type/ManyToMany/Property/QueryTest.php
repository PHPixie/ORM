<?php

namespace PHPixie\Tests\ORM\Relationships\Type\ManyToMany\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Property\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Property\QueryTest
{
    protected $config;

    public function setUp()
    {
        $this->config = $this->config();
        parent::setUp();
    }

    /**
     * @covers ::add
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testModifyItemLink()
    {
        $this->modifyLinkTest('add', 'link', 'left');
        $this->modifyLinkTest('add', 'link', 'right');
        $this->modifyLinkTest('remove', 'unlink', 'left');
        $this->modifyLinkTest('remove', 'unlink', 'right');
    }

    /**
     * @covers ::removeAll
     * @covers ::<protected>
     */
    public function testRemoveAll()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkAllPlan', $plan, array($this->side, $this->query), 0);        
        $this->assertEquals($this->property, $this->property->removeAll());
    }
    
    protected function modifyLinkTest($method, $action, $type)
    {
        $this->method($this->side, 'type', $type, array(), 1);
        $item = $this->getEntity();
        $plan = $this->getPlan();

        $args = $this->reorderArgs(array($this->config, $this->query, $item), $type);

        $this->method($this->handler, $action.'Plan', $plan, $args, 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'resetProperties', null, array($this->side, $item), 1);

        $this->assertEquals($this->property, $this->property->$method($item));
    }

    protected function reorderArgs($params, $type)
    {
        if($type === 'left') {
            $count = count($params);
            $param = $params[$count - 1];
            $params[$count - 1] = $params[$count - 2];
            $params[$count - 2] = $param;
        }
        return $params;
    }

    protected function prepareQuery()
    {
        $query = $this->getQuery();
        $this->method($this->handler, 'query', $query, array($this->side, $this->query), 0);
        return $query;
    }

    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }

    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config');
    }

    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Property\Query($this->handler, $this->side, $this->query);
    }

}
