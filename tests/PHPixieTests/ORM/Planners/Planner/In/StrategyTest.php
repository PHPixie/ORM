<?php

namespace PHPixieTests\ORM\Planners\Planner\In;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\In\Strategy
 */
abstract class StrategyTest extends \PHPixieTests\AbstractORMTest
{
    protected $steps;
    protected $strategy;
    
    public function setUp()
    {
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->strategy = $this->getStrategy();
    }
    
    abstract public function testIn();
    abstract protected function getStrategy();
}
