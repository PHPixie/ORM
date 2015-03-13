<?php

namespace PHPixie\Tests\ORM\Steps\Step\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Count
 */
class CountTest extends \PHPixie\Tests\ORM\Steps\Step\QueryTest
{

    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecuteException()
    {
        $this->method($this->query, 'execute', null, array(), 0);
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->execute();
    }
    
    /**
     * @covers ::count
     * @covers ::<protected>
     */
    public function testCountException()
    {
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->count();
    }
    
    /**
     * @covers ::execute
     * @covers ::count
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $this->method($this->query, 'execute', 3, array(), 0);
        $this->step->execute();
        $this->assertEquals(3, $this->step->count());
    }
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Query\Count($this->query);
    }
    
    protected function query()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\Count');
    }
}