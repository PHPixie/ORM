<?php

namespace PHPixieTests\ORM\Steps\Step\Pivot;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Pivot\Cartesian
 */
class CartesianTest extends \PHPixieTests\ORM\Steps\StepTest
{
    protected $resultFilters;
    
    public function setUp()
    {
        $this->resultFilters = array(
            $this->quickMock('\PHPixie\ORM\Steps\Result\Filter', array('getFilteredData')),
            $this->quickMock('\PHPixie\ORM\Steps\Result\Filter', array('getFilteredData'))
        );
        
        $this->method($this->resultFilters[0], 'getFilteredData', array(
            (object) array('a' => 1, 'b' => 2),
            (object) array('a' => 3, 'b' => 4)
        ));
        
        $this->method($this->resultFilters[1], 'getFilteredData', array(
            (object) array('a' => 5, 'c' => 6),
            (object) array('a' => 7, 'c' => 8)
        ));
        
        parent::setUp();
    }
    
    /**
     * @covers ::<protected>
     * @covers ::execute
     * @covers ::product
     */
    public function testProduct()
    {
        $step = $this->step;
        $this->assertException(function() use($step){
            $step->product();
        }, '\PHPixie\ORM\Exception\Plan');
        
        $step->execute();
        $this->assertEquals(array(
            array(1,2,5,6),
            array(1,2,7,8),
            array(3,4,5,6),
            array(3,4,7,8),
        ), $step->product());
    }
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Pivot\Cartesian($this->resultFilters);
    }
}