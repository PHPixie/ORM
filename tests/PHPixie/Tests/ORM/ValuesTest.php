<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Values
 */
class ValuesTest extends \PHPixie\Test\Testcase
{
    protected $values;
    
    public function setUp()
    {
        $this->values = new \PHPixie\ORM\Values();
    }

    /**
     * @covers ::orderBy
     * @covers ::<protected>
     */
    public function testOrderBy()
    {
        $orderBy = $this->values->orderBy('pixie', 'desc');
        $this->assertInstance($orderBy, '\PHPixie\ORM\Values\OrderBy');
        
        $this->assertSame('pixie', $orderBy->field());
        $this->assertSame('desc', $orderBy->direction());
    }
    
    /**
     * @covers ::preload
     * @covers ::<protected>
     */
    public function testPreload()
    {
        $preload = $this->values->preload();
        $this->assertInstance($preload, '\PHPixie\ORM\Values\Preload', array(
            'values' => $this->values
        ));
    }
    
    /**
     * @covers ::preloadProperty
     * @covers ::<protected>
     */
    public function testPreloadProperty()
    {
        $property = $this->values->preloadProperty('pixie');
        $this->assertInstance($property, '\PHPixie\ORM\Values\Preload\Property');
        
        $this->assertSame('pixie', $property->propertyName());
    }
    
    /**
     * @covers ::cascadingPreloadProperty
     * @covers ::<protected>
     */
    public function testCascadingPreloadProperty()
    {
        $property = $this->values->cascadingPreloadProperty('pixie');
        $this->assertInstance($property, '\PHPixie\ORM\Values\Preload\Property\Cascading');
        
        $this->assertSame('pixie', $property->propertyName());
        $this->assertInstanceOf('\PHPixie\ORM\Values\Preload', $property->preload());
    }
    
    /**
     * @covers ::update
     * @covers ::<protected>
     */
    public function testUpdate()
    {
        $update = $this->values->update();
        $this->assertInstance($update, '\PHPixie\ORM\Values\Update',array(
            'values' => $this->values
        ));
    }
    
    /**
     * @covers ::updateBuilder
     * @covers ::<protected>
     */
    public function testUpdateBuilder()
    {
        $query = $this->abstractMock('\PHPixie\ORM\Models\Type\Database\Query');
        
        $update = $this->values->updateBuilder($query);
        $this->assertInstance($update, '\PHPixie\ORM\Values\Update\Builder',array(
            'values' => $this->values,
            'query' => $query,
        ));
    }
    
    /**
     * @covers ::updateIncrement
     * @covers ::<protected>
     */
    public function testUpdateIncrement()
    {
        $increment = $this->values->updateIncrement(5);
        $this->assertInstance($increment, '\PHPixie\ORM\Values\Update\Increment');
        
        $this->assertSame(5, $increment->amount());
    }
    
    
    /**
     * @covers ::updateRemove
     * @covers ::<protected>
     */
    public function testUpdateRemove()
    {
        $remove = $this->values->updateRemove();
        $this->assertInstance($remove, '\PHPixie\ORM\Values\Update\Remove');
    }
}