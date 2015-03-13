<?php

namespace PHPixie\Tests\ORM\Plans\Plan\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan\Query\Count
 */
class CountTest extends \PHPixie\Tests\ORM\Plans\Plan\QueryTest
{
    /**
     * @covers \PHPixie\ORM\Plans\Plan::__construct
     * @covers \PHPixie\ORM\Plans\Plan\Query::__construct
     * @covers ::__construct
     */
    public function testConstruct() {
    
    }
    
    /**
     * @covers \PHPixie\ORM\Plans\Plan::execute
     * @covers \PHPixie\ORM\Plans\Plan\Query::execute
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $this->prepareExecute();
        $this->method($this->queryStep, 'count', 5, array(), 'once');
        $this->assertEquals(5, $this->plan->execute());
    }
    
    protected function queryStep()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Count');
    }
    
    protected function getPlan()
    {
        return new \PHPixie\ORM\Plans\Plan\Query\Count($this->plans, $this->queryStep);
    } 
}