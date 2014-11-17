<?php

namespace PHPixieTests\ORM\Plans\Plan\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan\Query\Loader
 */
class LoaderTest extends \PHPixieTests\ORM\Plans\Plan\QueryTest
{
    protected $preloadPlan;
    protected $loader;
    
    public function setUp()
    {
        $this->preloadPlan = $this->abstractMock('\PHPixie\ORM\Plans\Plan');
        $this->loader = $this->loader();
        parent::setUp();
        
        $this->steps[] = $this->step(array());
    }
    
    /**
     * @covers \PHPixie\ORM\Plans\Plan::__construct
     * @covers \PHPixie\ORM\Plans\Plan\Query::__construct
     * @covers ::__construct
     */
    public function testConstruct() {
    
    }
    
    /**
     * @covers ::preloadPlan
     * @covers ::<protected>
     */
    public function testPreloadPlan()
    {
        $this->assertSame($this->preloadPlan, $this->plan->preloadPlan());
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
        $this->assertEquals($this->loader, $this->plan->execute());
    }
    
    protected function addSteps($withConnections = false)
    {
        $steps = array_slice($this->steps, 0, 5);
        $this->method($this->requiredPlan, 'steps', $steps);
        
        $steps = array_slice($this->steps, 6, 1);
        $this->method($this->preloadPlan, 'steps', $steps);
        
        if($withConnections) {
            $this->method($this->queryStep, 'usedConnections', array());
            $this->method($this->steps[6], 'usedConnections', array());
        }
    }
    
    protected function loader()
    {
        $loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResultStep');
        $queryStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
        $this->method($loader, 'reusableResultStep', $queryStep, array());
        return $loader;
    }
    
    protected function queryStep()
    {
        return $this->loader->reusableResultStep();
    }
    
    protected function getPlan()
    {
        return new \PHPixie\ORM\Plans\Plan\Query\Loader($this->transaction, $this->requiredPlan, $this->preloadPlan, $this->loader);
    } 
}