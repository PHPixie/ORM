<?php

namespace PHPixie\Tests\ORM\Planners\Planner\In\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Planner\In\Strategy\Subquery
 */
class SubqueryTest extends \PHPixie\Tests\ORM\Planners\Planner\In\StrategyTest
{
    public function testIn()
    {
        $connection = $this->getConnection();
        $this->method($this->query, 'connection', $connection, array());
                      
        foreach(array(
                array(array(), array('and', false)),
                array(array('or', true), array('or', true))
            ) as $key => $params){
            
            $wrapperQuery = $this->getQuery();
            $this->method($connection, 'selectQuery', $wrapperQuery, array(), 0);
            $this->method($wrapperQuery, 'table', null, array($this->subquery, 'in'.$key), 0);
                
            $this->method($this->subquery, 'fields', null, array(array('fairy')), 0);
            $operatorParams = array_merge(array('pixie', $wrapperQuery), $params[1]);
            $this->method($this->query, 'addInOperatorCondition', null, $operatorParams, 1);
            
            $callParams = array_merge(array($this->query, 'pixie', $this->subquery, 'fairy', $this->plan), $params[0]);
            call_user_func_array(array($this->strategy, 'in'), $callParams);;
        }
    }
    
    protected function getConnection()
    {
        return $this->abstractMock('\PHPixie\Database\Type\SQL\Connection');
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
