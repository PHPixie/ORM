<?php

namespace PHPixieTests\ORM\Conditions\Condition;

/**
 * @coversDefaultClass \PHPixie\ORM\Conditions\Condition\In
 */
class InTest extends \PHPixieTests\ORM\Conditions\Condition\ImplementationTest
{
    protected $items = array();
    
    public function setUp()
    {
        for($i=0; $i<3; $i++) {
            $this->items[] = $this->quickMock('\PHPixie\ORM\Conditions\Condition\In', array());
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
     * @covers ::items
     * @covers ::<protected>
     */
    public function testItems()
    {
        $this->assertSame($this->items, $this->condition->items());
    }
    
    protected function condition()
    {
        return new \PHPixie\ORM\Conditions\Condition\In($this->items);
    }
}