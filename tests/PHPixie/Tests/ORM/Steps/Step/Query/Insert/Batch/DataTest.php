<?php

namespace PHPixie\Tests\ORM\Steps\Step\Query\Insert\Batch;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data
 */
abstract class DataTest extends \PHPixie\Tests\ORM\Steps\StepTest
{
    /**
     * @covers::data
     * @covers::<protected>
     */
    public function testDataException()
    {
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->data();
    }
}