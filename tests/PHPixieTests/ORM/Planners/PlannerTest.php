<?php

namespace PHPixieTests\ORM\Planners;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner
 */
abstract class PlannerTest extends \PHPixieTests\AbstractORMTest
{
    protected $strategies;
    protected $steps;
    protected $planner;
    
    public function setUp()
    {
        $this->strategies = $this->quickMock('\PHPixie\ORM\Planners\Strategies');
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
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
