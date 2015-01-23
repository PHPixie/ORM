<?php

namespace PHPixieTests\ORM\Planners\Planner\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\Query\Strategy
 */
abstract class StrategyTest extends \PHPixieTests\AbstractORMTest
{
    protected $strategy;
    
    public function setUp()
    {
        $this->strategy = $this->strategy();
    }
    
    abstract public function testSetSource();
    abstract public function testSetBatchData();
    abstract protected function strategy();
}
