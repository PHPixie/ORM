<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Type\Many\Property\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Items
 */
class ItemsTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\QueryTest
{
    
    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAdd()
    {
        $this->modifyTest('add');
    }
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->modifyTest('remove');
    }
    
    /**
     * @covers ::removeAll
     * @covers ::<protected>
     */
    public function testRemoveAll()
    {
        $plan = $this->getPlan();
        $this->method($this->handler, 'unlinkItemsPlan', $plan, array($this->config, $this->query), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->assertSame($this->property, $this->property->removeAll());
    }
    
    protected function modifyTest($action)
    {
        $method = $action === 'add' ? 'linkPlan' : 'unlinkPlan';
        
        $item = $this->getEntity();
        $plan = $this->getPlan();
        $this->method($this->handler, $method, $plan, array($this->config, $this->query, $item), 0);
        $this->method($plan, 'execute', null, array(), 0);
        $this->method($this->handler, 'resetProperties', $plan, array($this->side, $item), 1);
        $this->assertSame($this->property, $this->property->$action($item));
    }
    
    protected function getEntity()
    {
        return $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Entity');
    }
    
    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Property\Query\Items($this->handler, $this->side, $this->query);
    }
    
    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Type\OneTo\Type\Many\Side\Config');
    }
}