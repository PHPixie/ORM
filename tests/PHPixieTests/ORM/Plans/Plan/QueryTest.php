<?php

namespace PHPixieTests\ORM\Plans\Plan;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan\Query
 */
class QueryTest extends \PHPixieTests\ORM\Plans\PlanTest
{
    protected $requiredPlan;
    protected $queryStep;
    
    public function setUp()
    {
        $this->requiredPlan = $this->abstractMock('\PHPixie\ORM\Plans\Plan');
        $this->queryStep = $this->queryStep();
        parent::setUp();
        
        $this->steps[] = $this->queryStep;
    }
    
    /**
     * @covers \PHPixie\ORM\Plans\Plan::__construct
     * @covers ::__construct
     */
    public function testConstruct() {
    
    }
    
    /**
     * @covers ::requiredPlan
     * @covers ::<protected>
     */
    public function testPlans()
    {
        $this->assertSame($this->requiredPlan, $this->plan->requiredPlan());
    }
    
    /**
     * @covers \PHPixie\ORM\Plans\Plan::steps
     * @covers \PHPixie\ORM\Plans\Plan\Query::steps
     * @covers ::steps
     * @covers ::<protected>
     */
    public function testSteps()
    {
        parent::testSteps();
    }
    
    protected function addSteps($withConnections = false)
    {
        $steps = array_slice($this->steps, 0, 5);
        $this->method($this->requiredPlan, 'steps', $steps);
        if($withConnections) {
            $this->method($this->queryStep, 'usedConnections', array());
        }
    }
    
    protected function queryStep()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Step\Query');
    }
    
    protected function getPlan()
    {
        return new \PHPixie\ORM\Plans\Plan\Query($this->transaction, $this->requiredPlan, $this->queryStep);
    }    
}