<?php

namespace PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\Entity;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Type\OneTo\Property\Entity\Single
 */
abstract class SingleTest extends \PHPixie\Tests\ORM\Relationships\Type\OneTo\Property\EntityTest
{
    /**
     * @covers ::set
     * @covers ::<protected>
     */
    public function testSet()
    {
        $this->setTest();
    }

    protected function setTest()
    {
        $value = $this->getValue();
        $plan = $this->prepareLinkPlan($value);

        $this->method($plan, 'execute', null, array(), 0);
        $this->prepareSetProperties($value);

        $this->assertSame($this->property, $this->property->set($value));

        $this->prepareRemove();
        $this->property->set(null);

        $value = $this->getValue(true);
        $this->prepareRemove();
        $this->property->set($value);
    }

    /**
     * @covers ::value
     * @covers ::<protected>
     */
    public function testDeletedValue()
    {
        $entity = $this->getEntity();
        $this->method($entity, 'isDeleted', true, array(), 0);
        $this->prepareLoad($entity);
        $this->assertSame(null, $this->property->value());
    }
    
    /**
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testRemove()
    {
        $this->prepareRemove();
        $this->assertSame($this->property, $this->property->remove());
    }

    /**
     * @covers ::asData
     * @covers ::<protected>
     */
    public function testAsData()
    {
        $data = new \stdClass;
        $value = $this->getValue();
        
        $this->prepareLoad($value);
        $this->method($value, 'asObject', $data, array(false), 1);
        $this->assertSame($data, $this->property->asData());

        $this->method($value, 'asObject', $data, array(true), 1);
        $this->assertSame($data, $this->property->asData(true));
    }

    protected function prepareRemove()
    {
        $plan = $this->prepareUnlinkPlan();
        $this->method($plan, 'execute', null, array(), 0);
        $this->prepareUnsetProperties();
    }

    protected function getValue($isDeleted = false)
    {
        $value = $this->getEntity();
        $this->method($value, 'isDeleted', $isDeleted, array());
        return $value;
    }

    abstract protected function prepareLinkPlan($value);
    abstract protected function prepareSetProperties($value);
    abstract protected function prepareUnlinkPlan();
    abstract protected function prepareUnsetProperties();
}
