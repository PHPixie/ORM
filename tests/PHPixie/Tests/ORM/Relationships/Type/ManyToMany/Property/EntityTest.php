<?php

namespace PHPixie\Tests\ORM\Relationships\Type\ManyToMany\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\ManyToMany\Property\Entity
 */
class EntityTest extends \PHPixie\Tests\ORM\Relationships\Relationship\Implementation\Property\EntityTest
{
    protected $config;

    public function setUp()
    {
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
        $this->method($this->handler, 'query', $query, array($this->side, $this->entity), 0);
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
        $this->method($this->handler, 'unlinkAllPlan', $plan, array($this->side, $this->entity), 0);
        $this->method($this->handler, 'unlinkAllProperties', $plan, array($this->side, $this->entity), 1);
        
        $this->assertEquals($this->property, $this->property->removeAll());
    }
    
    /**
     * @covers ::asData
     * @covers ::<protected>
     */
    public function testAsData()
    {
        $value = new \ArrayObject;
        $this->prepareLoad($value);
        for($i=0;$i<3;$i++){
            $entity = $this->getEntity();
            if($i != 1) {
                $this->method($entity, 'isDeleted', false, array());
                $value[]=$entity;
                $this->method($entity, 'asObject', $i, array(false), 1);
                $this->method($entity, 'asObject', $i, array(true), 3);
                
            }else{
                $this->method($entity, 'isDeleted', true, array());
            }
        }
        
        $this->assertEquals(array(0, 2), $this->property->asData());
        $this->assertEquals(array(0, 2), $this->property->asData(true));
    }

    protected function modifyLinkTest($method, $action, $type)
    {
        $this->method($this->side, 'type', $type, array(), 1);
        $item = $this->getEntity();
        $plan = $this->getPlan();
        
        $args = $this->reorderArgs(array($this->config, $this->entity, $item), $type);
        $this->method($this->handler, $action.'Plan', $plan, $args, 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, $action.'Properties', null, $args, 1);

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

    protected function prepareLoad($value)
    {
        $this->method($this->handler, 'loadProperty', $this->setValueCallback($value), array($this->side, $this->entity), 0);
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
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Query');
    }
    
    protected function getEntity()
    {
        return $this->quickMock('\PHPixie\ORM\Models\Type\Database\Entity');
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
        return new \PHPixie\ORM\Relationships\Type\ManyToMany\Property\Entity($this->handler, $this->side, $this->entity);
    }

}
