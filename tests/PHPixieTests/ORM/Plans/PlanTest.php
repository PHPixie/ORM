<?php

namespace PHPixieTests\ORM\Plans;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan
 */
abstract class PlanTest extends \PHPixieTests\AbstractORMTest
{
    protected $transaction;
    protected $connections;
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
        $this->method($this->transaction, 'begin', null, array($this->connections), 0);
        $this->method($this->transaction, 'rollback', null, array($this->connections), 1);
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
    
    protected function prepareExecute()
    {
        $this->addSteps(true);
        $this->method($this->transaction, 'begin', null, array($this->connections), 0);
        $this->method($this->transaction, 'commit', null, array($this->connections), 1);
        foreach($this->steps as $step) {
            $step
                ->expects($this->once())
                ->method('execute')
                ->with();
        }
    }
    
    abstract protected function getPlan();
    abstract protected function addSteps($withConnections = false);
}