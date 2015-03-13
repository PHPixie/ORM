<?php

namespace PHPixie\Tests\ORM\Plans\Plan;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan\Query
 */
class QueryTest extends \PHPixie\Tests\ORM\Plans\PlanTest
{
    protected $queryStep;
    
    public function setUp()
    {
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
        $stepsPlan = $this->prepareStepsPlan();
        $this->assertSame($stepsPlan, $this->plan->requiredPlan());
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
    
    /**
     * @covers ::steps
     * @covers ::<protected>
     */
    public function testOnlyQueryStep()
    {
        $this->assertSame(array($this->queryStep), $this->plan->steps());
    }
    
    /**
     * @covers ::queryStep
     * @covers ::<protected>
     */
    public function testQueryStep()
    {
        $this->assertSame($this->queryStep, $this->plan->queryStep());
    }
    
    protected function addSteps($withConnections = false)
    {
        $requiredPlan = $this->prepareStepsPlan();
        $this->plan->requiredPlan();
        
        $steps = array_slice($this->steps, 0, 5);
        $this->method($requiredPlan, 'steps', $steps, array());
        
        if($withConnections) {
            $this->method($this->queryStep, 'usedConnections', array());
        }
    }
    
    protected function prepareStepsPlan($at = 0)
    {
        $steps = $this->stepsPlan();
        $this->method($this->plans, 'steps', $steps, array(), $at);
        return $steps;
    }

    protected function stepsPlan()
    {
        return $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    protected function queryStep()
    {
        return $this->abstractMock('\PHPixie\ORM\Steps\Step\Query');
    }
    
    protected function getPlan()
    {
        return new \PHPixie\ORM\Plans\Plan\Query($this->plans, $this->queryStep);
    }    
}