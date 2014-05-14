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
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testReuse()
    {
        $this->setStepResult();
        $iterator = $this->step->getIterator();
        $iterator->next();
        $nextIterator = $this->step->getIterator();
        $this->assertEquals(true, $iterator !== $nextIterator);
        $this->assertNotEquals($iterator->current(), $nextIterator->current());
    }
    
}