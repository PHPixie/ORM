<?php

namespace PHPixie\Tests\ORM\Plans\Plan\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Plan\Query\Loader
 */
class LoaderTest extends \PHPixie\Tests\ORM\Plans\Plan\QueryTest
{
    protected $loader;
    
    public function setUp()
    {
        $this->loader = $this->quickMock('\PHPixie\ORM\Loaders\Loader\Repository\ReusableResult');
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
        $stepsPlan = $this->prepareStepsPlan();
        $this->assertSame($stepsPlan, $this->plan->preloadPlan());
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
        parent::addSteps($withConnections);
        
        $preloadPlan = $this->prepareStepsPlan();
        $this->plan->preloadPlan();
        
        $steps = array_slice($this->steps, 6, 1);
        $this->method($preloadPlan, 'steps', $steps, array());
        
        if($withConnections) {
            $this->method($this->queryStep, 'usedConnections', array());
            $this->method($this->steps[6], 'usedConnections', array());
        }
    }
    
    protected function queryStep()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result\Reusable');
    }
    
    protected function getPlan()
    {
        return new \PHPixie\ORM\Plans\Plan\Query\Loader($this->plans, $this->queryStep, $this->loader);
    } 
}