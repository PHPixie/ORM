<?php

namespace PHPixieTests\ORM\Planners\Planner\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\Query\Strategy
 */
abstract class StrategyTest extends \PHPixieTests\AbstractORMTest
{
    protected $steps;
    protected $strategy;
    
    public function setUp()
    {
        $this->strategy = $this->getStrategy();
    }
    
    abstract public function testSetSource();
    abstract public function testSetBatchData();
    abstract protected function getStrategy();
}
