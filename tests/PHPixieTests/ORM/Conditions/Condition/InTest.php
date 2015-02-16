<?php

namespace PHPixieTests\ORM\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\In
 */
class InTest extends \PHPixieTests\ORM\Conditions\Condition\ImplementationTest
{
    protected $modelName = 'pixie';
    protected $items = array();
    
    public function setUp()
    {
        for($i=0; $i<3; $i++) {
            $this->items[] = $this->item();
        }
        
        parent::setUp();
    }
    
    /**
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }

    /**
     * @covers ::add
     * @covers ::<protected>
     */
    public function testAdd()
    {
        $item = $this->item();
        $this->condition->add($item);
        
        $addedItems = $this->condition->items();
        $this->assertSame($item, end($addedItems));
        
        $items = array(
            $this->item(),
            $this->item()
        );
        $this->condition->add($items);
        
        $addedItems = $this->condition->items();
        $this->assertSame($items, array_slice($addedItems, -2));
        
        $invalidItem = $this->item(false);
        $this->setExpectedException('\PHPixie\ORM\Exception\Builder');
        $this->condition->add($invalidItem);
    }
    
    /**
     * @covers ::items
     * @covers ::<protected>
     */
    public function testItems()
    {
        $this->assertSame($this->items, $this->condition->items());
    }
    
    /**
     * @covers ::modelName
     * @covers ::<protected>
     */
    public function testModelName()
    {
        $this->assertSame($this->modelName, $this->condition->modelName());
    }
    
    protected function item($valid = true)
    {
        $item = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In', array());
        
        $modelName = $valid ? $this->modelName : 'fairy';
        $item
            ->expects($this->any())
            ->method('modelName')
            ->with()
            ->will($this->returnValue($modelName));
        
        return $item;
    }
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\In($this->modelName, $this->items);
    }
}