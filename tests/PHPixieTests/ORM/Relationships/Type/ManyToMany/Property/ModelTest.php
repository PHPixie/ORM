<?php

namespace PHPixieTests\ORM\Relationships\Type\ManyToMany\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Property\Model
 */
class ModelTest extends \PHPixieTests\ORM\Relationships\Relationship\Property\ModelTest
{
    protected $config;
    protected $model;

    public function setUp()
    {
        $this->model  = $this->getModel();
        $this->config = $this->config();
        parent::setUp();
    }

    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $query = $this->getQuery();
        $this->method($this->handler, 'query', $query, array($this->side, $this->model), 0);
        $this->assertEquals($query, $this->property->query());
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
        $this->method($this->handler, 'unlinkAllPlan', $plan, array($this->side, $this->model), 0);
        $this->method($this->handler, 'unlinkAllProperties', $plan, array($this->side, $this->model), 1);
        
        $this->assertEquals($this->property, $this->property->removeAll());
    }
    
    /**
     * @covers ::AsData
     * @covers ::<protected>
     */
    public function testAsData()
    {
        $this->prepareLoad(new \ArrayObject);
        for($i=0;$i<3;$i++){
            $model = $this->quickMock('stdClass', array('asObject'));
            $this->value[]=$model;
            $this->method($model, 'asObject', $i, array(true), 0);
            $this->method($model, 'asObject', $i, array(false), 1);
        }
        
        $this->assertEquals(array(0, 1, 2), $this->property->asData());
        $this->assertEquals(array(0, 1, 2), $this->property->asData(false));
    }

    public function modifyLinkTest($method, $action, $type)
    {
        $this->method($this->side, 'type', $type, array());
        $item = $this->getModel();
        $plan = $this->getPlan();

        $args = $this->reorderArgs(array($this->config, $this->model, $item), $type);

        $this->method($this->handler, $action.'Plan', $plan, $args, 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, $action.'Properties', null, $args, 1);

        $this->assertEquals($this->model, $this->property->$method($item));
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

    protected function prepareLoad()
    {
        $this->value = $this->value();
        $this->method($this->handler, 'loadProperty', $this->value, array($this->side, $this->model), 0);
    }

    protected function value()
    {
        return $this->getValue();
    }

    protected function getValue()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
    }

    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Query');
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

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\ManyToMany\Side\Config');
    }

    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Property\Model($this->handler, $this->side, $this->model);
    }

}
