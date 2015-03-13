<?php

namespace PHPixie\Tests\ORM\Steps\Query\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Result\Iterator
 */
class IteratorTest extends \PHPixie\Tests\ORM\Steps\Step\Query\ResultTest
{
    protected $iterator;
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testReuse()
    {
        $this->step = $this->getStepInstance();
        $this->setStepResult();
        $this->step->getIterator();
        
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->getIterator();
    }
    
    /**
     * @covers ::asArray
     * @covers ::<protected>
     */
    public function testAsArray()
    {
        $this->step = $this->getStepInstance();
        $this->setStepResult();
        $this->method($this->result, 'asArray', $this->rows, array(), 0);
        $this->assertSame($this->rows, $this->step->asArray());
    }
    
    protected function prepareResult()
    {
        return 0;
    }
    
    protected function getStepInstance()
    {
        return new \PHPixie\ORM\Steps\Step\Query\Result\Iterator($this->query);
    }
    
    protected function getStep()
    {
        $this->iterator = new \ArrayIterator($this->rows);
        $mock = $this->getMock('\PHPixie\ORM\Steps\Step\Query\Result\Iterator', array('getIterator'), array($this->query));
        $this->method($mock, 'getIterator', $this->iterator, array());
        return $mock;
    }
    
}