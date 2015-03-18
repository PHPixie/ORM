<?php

namespace PHPixie\Tests\ORM\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\In
 */
class InTest extends \PHPixie\Tests\ORM\Conditions\Condition\ImplementationTest
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
            5,
            $this->item(),
            $this->item()
        );
        $this->condition->add($items);
        
        $addedItems = $this->condition->items();
        $this->assertSame($items, array_slice($addedItems, -3));
        
        $condition = $this->condition;
        $invalidItems = array(
            $this->item(false),
            $this->getEntity(true)
        );
        
        foreach($invalidItems as $invalidItem) {
            $this->assertException(function() use($condition, $invalidItem){
                $condition->add($invalidItem);
            }, '\PHPixie\ORM\Exception\Builder');
        }
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
    
    protected function item($validModelName = true)
    {
        $item = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In', array());
        
        $modelName = $validModelName ? $this->modelName : 'fairy';
        $this->method($item, 'modelName', $modelName, array());
        
        return $item;
    }
    
    protected function getEntity($isNew = false)
    {
        $entity = $this->quickMock('\PHPixie\ORM\Models\Type\Database\Entity', array());
        $this->method($entity, 'modelName', $this->modelName, array());
        $this->method($entity, 'isNew', $isNew, array());
        return $entity;
    }
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\In($this->modelName, $this->items);
    }
}