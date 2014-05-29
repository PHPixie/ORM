<?php

namespace PHPixieTests\ORM\Plans\Plan\Composite;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan\Composite\Loader
 */
class LoaderTest extends \PHPixieTests\ORM\Plans\Plan\CompositeTest
{
    protected $subplans = array();
    
    public function setUp()
    {
        parent::setUp();
        $this->subplans = array(
            $this->subPlan(),
            $this->subPlan(),
        );
    }
    
    protected function subPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Step', array('steps'));
    }
    
    protected function getPlan()
    {
        return new \PHPixie\ORM\Plans\Plan\Composite\Loader($this->plans);
    }
    
    /**
     * @covers ::setResultStep
     * @covers ::resultStep
     * @covers ::<protected>
     */
    public function testResultStep()
    {
        $this->plan->setResultStep($this->steps[0]);
        $this->assertEquals($this->steps[0], $this->plan->resultStep());
    }
    
    /**
     * @covers ::requiredPlan
     * @covers ::preloadPlan
     * @covers ::<protected>
     */
    public function testPlans()
    {
        $this->setPlans();
        foreach(array('required', 'preload') as $key => $plan) {
            $method = $plan.'Plan';
            $this->assertEquals($this->subplans[$key], $this->plan->$method());
            $this->assertEquals($this->subplans[$key], $this->plan->$method());
        }
    }
    
    protected function setPlans($n = 2)
    { 
        for ($i = 0; $i < $n; $i++)
            $this->method($this->plans, 'plan', $this->subplans[$i], array(), $i);
    }
    
    protected function addSteps()
    {
        $this->setPlans();
        $this->plan->requiredPlan();
        $this->plan->preloadPlan();
        $this->method($this->subplans[0], 'steps', array(
                                                    $this->steps[0],
                                                    $this->steps[1]
                                                ));
        $this->plan->setResultStep($this->steps[2]);
        $this->method($this->subplans[1], 'steps', array(
                                                    $this->steps[3],
                                                    $this->steps[4]
                                                ));
    }
    
    
    
}