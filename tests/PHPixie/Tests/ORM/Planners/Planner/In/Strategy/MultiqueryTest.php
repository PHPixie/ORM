<?php

namespace PHPixie\Tests\ORM\Planners\Planner\In\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\In\Strategy\Multiquery
 */
class MultiqueryTest extends \PHPixie\Tests\ORM\Planners\Planner\In\StrategyTest
{
    public function testIn()
    {
        $placeholder = $this->quickMock('\PHPixie\ORM\Conditions\Condition\Placeholder');
        $resultStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result');
        $inStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\In');
        
        foreach(array(
                array(array(), array('and', false)),
                array(array('or', true), array('or', true))
            ) as $params){
            
            $this->method($this->subquery, 'fields', null, array(array('fairy')), 0);
            $this->method($this->steps, 'iteratorResult', $resultStep, array($this->subquery), 0);
            $this->method($this->plan, 'add', null, array($resultStep), 0);
            $this->method($this->query, 'addPlaceholder', $placeholder, $params[1], 0);
            $this->method($this->steps, 'in', $inStep, array($placeholder, 'pixie', $resultStep, 'fairy'), 1);
            $this->method($this->plan, 'add', null, array($inStep), 1);
            
            $callParams = array_merge(array($this->query, 'pixie', $this->subquery, 'fairy', $this->plan), $params[0]);
            call_user_func_array(array($this->strategy, 'in'), $callParams);;
        }

    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Items');
    }
    
    protected function strategy()
    {
        return new \PHPixie\ORM\Planners\Planner\In\Strategy\Multiquery($this->steps);
    }
}
