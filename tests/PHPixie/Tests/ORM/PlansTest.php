<?php

namespace PHPixie\Tests\ORM;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans
 */
class PlansTest extends \PHPixie\Test\Testcase
{
    
    protected $plans;
    
    public function setUp()
    {
        $this->plans = new \PHPixie\ORM\Plans();
    }
    
    /**
     * @covers ::steps
     */
    public function testSteps()
    {
        $plan = $this->plans->steps();
        $this->assertInstance($plan, '\PHPixie\ORM\Plans\Plan\Steps', array(
            'plans' => $this->plans
        ));
    }

    /**
     * @covers ::query
     */
    public function testQuery()
    {
        $queryStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query');
        
        $plan = $this->plans->query($queryStep);
        $this->assertInstance($plan, '\PHPixie\ORM\Plans\Plan\Query', array(
            'plans'       => $this->plans,
            'queryStep'   => $queryStep
        ));
    }
    
    /**
     * @covers ::count
     */
    public function testCount()
    {
        $queryStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Count');
        
        $plan = $this->plans->count($queryStep);
        $this->assertInstance($plan, '\PHPixie\ORM\Plans\Plan\Query\Count', array(
            'plans'       => $this->plans,
            'queryStep'   => $queryStep
        ));
    }
    
    /**
     * @covers ::loader
     */
    public function testLoader()
    {
        $queryStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
        
        $plan = $this->plans->loader($queryStep, $loader);
        $this->assertInstance($plan, '\PHPixie\ORM\Plans\Plan\Query\Loader', array(
            'plans'       => $this->plans,
            'queryStep'   => $queryStep,
            'loader'      => $loader
        ));
    }

    /**
     * @covers ::transaction
     * @covers ::<protected>
     */
    public function testTransaction()
    {
        $connections = array(5);
        $transaction = $this->plans->transaction($connections);
        $this->assertInstance($transaction, '\PHPixie\ORM\Plans\Transaction', array(
            'connections' => $connections
        ));
    }

}