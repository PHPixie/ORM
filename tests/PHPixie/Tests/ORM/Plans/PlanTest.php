<?php

namespace PHPixie\Tests\ORM\Plans;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan
 */
abstract class PlanTest extends \PHPixie\Test\Testcase
{
    protected $plans;
    protected $connections;
    protected $plan;
    
    public function setUp()
    {
        $this->plans = $this->quickMock('\PHPixie\ORM\Plans');
        
        $this->connections = array(
            $this->getConnection(),
            $this->getConnection()
        );
        
        $this->steps = array(
            $this->step($this->connections),
            $this->step($this->connections),
            $this->step(array()),
            $this->step(array($this->connections[0])),
            $this->step(array($this->connections[1])),
        );
        
        $this->plan = $this->getPlan();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct() {
    
    }
    
    
    /**
     * @covers ::steps
     * @covers \PHPixie\ORM\Plans\Plan::steps
     * @covers ::<protected>
     */
    public function testSteps()
    {
        $this->addSteps();
        $this->assertEquals($this->steps, $this->plan->steps());
    }
    
    /**
     * @covers ::usedConnections
     * @covers ::<protected>
     */
    public function testUsedConnections()
    {
        $this->addSteps(true);
        $this->assertEquals($this->connections, $this->plan->usedConnections());
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $this->prepareExecute();
        $this->plan->execute();
    }
    
    /**
     * @covers ::execute
     * @covers \PHPixie\ORM\Plans\Plan::execute
     * @covers ::<protected>
     */
    public function testExecuteRollback()
    {
        $this->addSteps(true);
        
        $transaction = $this->quickMock('\PHPixie\ORM\Plans\Transaction');
        $this->method($this->plans, 'transaction', $transaction, array($this->connections));
        
        $this->method($transaction, 'begin', null, array(), 0);
        $this->method($transaction, 'rollback', null, array(), 1);
        foreach($this->steps as $step)
            $this->method($step, 'execute', function() {
                throw new \Exception("test");
            });
        
        $this->setExpectedException('\Exception');
        $this->plan->execute();
    }
    
    protected function step($connections)
    {
        $step = $this->quickMock('\PHPixie\ORM\Steps\Step', array('usedConnections', 'execute'));
        $this->method($step, 'usedConnections', $connections, array());
        return $step;
    }
    
    protected function prepareExecute()
    {
        $this->addSteps(true);
        
        $transaction = $this->quickMock('\PHPixie\ORM\Plans\Transaction');
        $this->method($this->plans, 'transaction', $transaction, array($this->connections));
        
        $this->method($transaction, 'begin', null, array(), 0);
        $this->method($transaction, 'commit', null, array(), 1);
        foreach($this->steps as $step) {
            $step
                ->expects($this->once())
                ->method('execute')
                ->with();
        }
    }
    
    protected function getConnection()
    {
        return $this->abstractMock('\PHPixie\Database\Connection');
    }
    
    abstract protected function getPlan();
    abstract protected function addSteps($withConnections = false);
}