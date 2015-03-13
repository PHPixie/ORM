<?php

namespace PHPixie\Tests\ORM\Steps\Step\Pivot;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Pivot\Cartesian
 */
class CartesianTest extends \PHPixie\Tests\ORM\Steps\Step\Query\Insert\Batch\DataTest
{
    protected $fields = array('a', 'b');
    protected $resultFilters;
    
    public function setUp()
    {
        $this->resultFilters = array(
            $this->quickMock('\PHPixie\ORM\Steps\Result\Filter', array('getFilteredData')),
            $this->quickMock('\PHPixie\ORM\Steps\Result\Filter', array('getFilteredData'))
        );
        
        $this->method($this->resultFilters[0], 'getFilteredData', array(
            array('a' => 1, 'b' => 2),
            array('a' => 3, 'b' => 4)
        ));
        
        $this->method($this->resultFilters[1], 'getFilteredData', array(
            array('a' => 5, 'c' => 6),
            array('a' => 7, 'c' => 8)
        ));
        
        parent::setUp();
    }
    

    /**
     * @covers ::fields
     * @covers ::<protected>
     */
    public function testFields()
    {
        $this->assertSame($this->fields, $this->step->fields());
    }
    
    /**
     * @covers ::execute
     * @covers ::data
     * @covers ::<protected>
     */
    public function testProduct()
    {
        $step = $this->step;
        $this->assertException(function() use($step){
            $step->data();
        }, '\PHPixie\ORM\Exception\Plan');
        
        $step->execute();
        $this->assertEquals(array(
            array(1,2,5,6),
            array(1,2,7,8),
            array(3,4,5,6),
            array(3,4,7,8),
        ), $step->data());
    }
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Pivot\Cartesian($this->fields, $this->resultFilters);
    }
}