<?php

namespace PHPixieTests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans
 */
class PlansTest extends \PHPixieTests\AbstractORMTest
{
    
    protected $plans;
    
    public function setUp()
    {
        $this->plans = new \PHPixie\ORM\Plans;
    }
    
    /**
     * @covers ::plan
     */
    public function testPlan()
    {
        $plan = $this->plans->plan();
        $this->assertInstanceOf('\PHPixie\ORM\Plans\Plan\Step', $plan);
        $this->assertAttributeEquals($this->plans, 'plans', $plan);
    }

    /**
     * @covers ::loader
     */
    public function testLoader()
    {
        $loaderPlan = $this->plans->loader();
        $this->assertInstanceOf('\PHPixie\ORM\Plans\Plan\Composite\Loader', $loaderPlan);
        $this->assertAttributeEquals($this->plans, 'plans', $loaderPlan);
    }

    /**
     * @covers ::transaction
     * @covers ::<protected>
     */
    public function testTransaction()
    {
        $transaction = $this->plans->transaction();
        $this->assertInstanceOf('\PHPixie\ORM\Plans\Transaction', $transaction);
        $this->assertEquals($transaction, $this->plans->transaction());
    }

}