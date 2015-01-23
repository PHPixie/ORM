<?php

namespace PHPixieTests\ORM\Planners\Planner\In\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\In\Strategy\Subquery
 */
class SubqueryTest extends \PHPixieTests\ORM\Planners\Planner\In\StrategyTest
{
    public function testIn()
    {
        foreach(array(
                array(array(), array('and', false)),
                array(array('or', true), array('or', true))
            ) as $params){
            
            $this->method($this->subquery, 'fields', null, array(array('fairy')), 0);
            $operatorParams = array_merge(array('pixie', $this->subquery), $params[1]);
            $this->method($this->query, 'addInOperatorCondition', null, $operatorParams, 0);
            
            $callParams = array_merge(array($this->query, 'pixie', $this->subquery, 'fairy', $this->plan), $params[0]);
            call_user_func_array(array($this->strategy, 'in'), $callParams);;
        }
    }
    
    protected function getQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Type\SQL\Query\Items');
    }
    
    protected function strategy()
    {
        return new \PHPixie\ORM\Planners\Planner\In\Strategy\Subquery($this->steps);
    }
}
