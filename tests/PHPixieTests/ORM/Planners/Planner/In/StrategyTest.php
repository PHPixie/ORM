<?php

namespace PHPixieTests\ORM\Planners\Planner\In;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\In\Strategy
 */
abstract class StrategyTest extends \PHPixieTests\AbstractORMTest
{
    protected $steps;
    protected $strategy;
    
    protected $query;
    protected $subquery;
    protected $plan;
    protected $builder;
    
    public function setUp()
    {
        $this->query = $this->abstractMock('\PHPixie\Database\Query\Items');
        $this->subquery = $this->abstractMock('\PHPixie\Database\Query\Type\Select');
        $this->plan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
        $this->builder = $this->quickMock('\PHPixie\Database\Conditions\Builder');
        
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->strategy = $this->getStrategy();
    }
    
    abstract public function testIn();
    abstract protected function getStrategy();
}
