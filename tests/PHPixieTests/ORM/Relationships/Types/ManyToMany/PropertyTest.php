<?php

namespace PHPixieTests\ORM\Relationships\Types\ManyToMany\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Types\ManyToMany\Property\Model
 */
abstract class ModelTest extends \PHPixieTests\ORM\Relationships\Relationship\Property\ModelTest
{
    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $query = $this->getQuery();
        $this->method($this->handler, 'query', $query, array($this->side, $this->model), 0);
        $this->assertEquals($query, $this->propertty->query());
    }


    public function modifyLinkTest($method, $action, $type)
    {
        $item = $this->getModel();
        $plan = $this->getPlan();
        $args = $this->reorderArgs(array($this->config, $this->model, $item), $type);

        $this->method($this->handler, $action.'Plan', $plan, $args, 0);
        $this->method($this->handler, 'execute', null, array(), 0);
        $this->method($this->handler, $action.'Properties', null, $args, 0);
        $this->assertEquals($this, $this->property->$method($item));
    }

    protected function reorderArgs($params, $type)
    {
        if($type === 'right') {
            $count = count($params);
            $param = $params[$count - 1];
            $params[$count - 1] = $params[$count - 2];
            $params[$count - 2] = $param;
        }

        return $params;
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Query');
    }
}
