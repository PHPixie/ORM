<?php

namespace PHPixieTests\ORM\Planners;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner
 */
abstract class PlannerTest extends \PHPixieTests\AbstractORMTest
{
    protected $strategies;
    protected $planner;
    
    public function setUp()
    {
        $this->strategies = $this->quickMock('\PHPixie\ORM\Planners\Strategies');
        $this->planner = $this->getPlanner();
    }
    
    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }
    
    abstract protected function getPlanner();
}