<?php

namespace PHPixieTests\ORM\Steps\Query\Result;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Result\SingleUse
 */
class SingleUseTest extends \PHPixieTests\ORM\Steps\Step\Query\ResultTest
{

    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Query\Result\SingleUse($this->query);
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
        $this->assertEquals($iterator, $nextIterator);
        $this->assertEquals($iterator->current(), $nextIterator->current());
    }
    
}