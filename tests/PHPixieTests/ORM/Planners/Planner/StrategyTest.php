<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Strategy
 */
abstract class StrategyTest extends \PHPixieTests\ORM\Planners\PlannerTest
{
    protected $strategies;
    
    public function setUp()
    {
        $this->strategies = $this->quickMock()
        parent::setUp();
    }
}