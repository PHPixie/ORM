<?php

namespace PHPixieTests\ORM\Steps\Query\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Result\Reusable
 */
class ReusableTest extends \PHPixieTests\ORM\Steps\Step\Query\ResultTest
{

    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Query\Result\Reusable($this->query);
    }

    /**
     * @covers ::getByOffset
     * @covers ::<protected>
     */
    public function testGetByOffset()
    {
        $step = $this->step;
        $this->assertException(function() use($step) {
            $step->getByOffset(1);
        }, '\PHPixie\ORM\Exception\Plan');
        
        $this->setStepResult();
        $this->assertSame($this->rows[1], $this->step->getByOffset(1));
        
        $this->assertException(function() use($step) {
            $step->getByOffset(3);
        }, '\Exception');
    }
    
    /**
     * @covers ::offsetExists
     * @covers ::<protected>
     */
    public function testOffsetExists()
    {
        $step = $this->step;
        $this->assertException(function() use($step) {
            $step->offsetExists(1);
        }, '\PHPixie\ORM\Exception\Plan');
        
        $this->setStepResult();
        $this->assertSame(true, $this->step->offsetExists(1));
        $this->assertSame(false, $this->step->offsetExists(3));
    }
        
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testReuse()
    {
        $this->setStepResult();
        $this->prepareIterator();
        $iterator = $this->step->getIterator();
        $iterator->next();
        $nextIterator = $this->step->getIterator();
        $this->assertEquals(true, $iterator !== $nextIterator);
        $this->assertNotEquals($iterator->current(), $nextIterator->current());
    }
    
}