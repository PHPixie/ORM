<?php

namespace PHPixieTests\ORM\Plans;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan
 */
abstract class PlanTest extends \PHPixieTests\AbstractORMTest
{
    protected $plans;
    protected $transaction;
    protected $connections;
    protected $steps;
    protected $plan;
    
    
    public function setUp()
    {
        $this->transaction = $this->quickMock('\PHPixie\ORM\Plans\Transaction', array('begin', 'commit', 'rollback'));
        $this->connections = array(
            $this->valueObject(),
            $this->valueObject()
        );
        
        $this->steps = array(
            $this->step($this->connections),
            $this->step($this->connections),
            $this->step(array()),
            $this->step(array($this->connections[0])),
            $this->step(array($this->connections[1])),
        );
        
        $this->plans = $this->quickMock('\PHPixie\ORM\Plans', array('transaction', 'plan'));
        $this->method($this->plans, 'transaction', $this->transaction);
        $this->plan = $this->getPlan();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct() {
    
    }
    
    
    /**
     * @covers ::steps
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
        $this->addSteps();
        $this->assertEquals($this->connections, $this->plan->usedConnections());
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $this->addSteps();
        $this->method($this->transaction, 'begin', null, 0, true, array($this->connections));
        $this->method($this->transaction, 'commit', null, 1, true, array($this->connections));
        foreach($this->steps as $step)
            $this->method($step, 'execute', null, 0);
        $this->plan->execute();
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecuteRollback()
    {
        $this->addSteps();
        $this->method($this->transaction, 'begin', null, 0, true, array($this->connections));
        $this->method($this->transaction, 'rollback', null, 1, true, array($this->connections));
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
        $this->method($step, 'usedConnections', $connections);
        return $step;
    }
    
    abstract protected function getPlan();
}