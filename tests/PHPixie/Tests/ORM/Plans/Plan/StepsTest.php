<?php

namespace PHPixie\Tests\ORM\Plans\Plan;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan\Steps
 */
class StepsTest extends \PHPixie\Tests\ORM\Plans\PlanTest
{
    protected function getPlan()
    {
        return new \PHPixie\ORM\Plans\Plan\Steps($this->plans);
    }
    
    protected function addSteps($withConnections = false)
    {
        foreach($this->steps as $step)
            $this->plan->add($step);
    }
    
    /**
     * @covers ::add
     */
    public function testAdd()
    {
        $this->plan->add($this->steps[0]);
        $this->plan->add($this->steps[1]);
        $this->assertEquals(array(
                                $this->steps[0],
                                $this->steps[1]
                            ), $this->plan->steps());
    }
    
    /**
     * @covers ::appendPlan
     */
    public function testAppendPlan()
    {
        $subplan = $this->getPlan();
        $this->plan->add($this->steps[0]);
        $subplan->add($this->steps[1]);
        $subplan->add($this->steps[2]);
        $this->plan->appendPlan($subplan);
        $this->assertEquals(array(
                                $this->steps[0],
                                $this->steps[1],
                                $this->steps[2]
                            ), $this->plan->steps());
    }
}