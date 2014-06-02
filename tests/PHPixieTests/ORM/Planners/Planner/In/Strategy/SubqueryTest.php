<?php

namespace PHPixieTests\ORM\Planners\Planner\In\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\In\Strategy\Subquery
 */
class SubqueryTest extends \PHPixie\ORM\Planners\Planner\Planner\In\StrategyTest
{
    public function testIn()
    {
        $query = $this->abstractMock('\PHPixie\Database\Query\Items');
        $subquery = $this->abstractMock('\PHPixie\Database\Query\Items');
        $plan = $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
        $builder = $this->quickMock('\PHPixie\Database\Conditions\Builder');
        
        $this->method($query, 'getWhereBuilder', $builder, array(), 0);
        $this->method($subquery, 'fields', null, array('pixie'), 0);
        $this->method($subquery, 'fields', null, array('pixie'), 0);
    }
    
    protected function getStrategy()
    {
        return \PHPixie\ORM\Planners\Planner\Planner\In\Strategy\Subquery($this->steps);
    }
}
