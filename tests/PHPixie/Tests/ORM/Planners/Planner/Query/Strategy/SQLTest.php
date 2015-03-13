<?php

namespace PHPixie\Tests\ORM\Planners\Planner\Query\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Query\Strategy\SQL
 */
class SQLTest extends \PHPixie\Tests\ORM\Planners\Planner\Query\StrategyTest
{
    /**
     * @covers ::setSource
     */
    public function testSetSource()
    {
        $query = $this->abstractMock('\PHPixie\Database\Type\SQL\Query');
        $this->method($query, 'table', null, array('pixie'), 0);
        $this->strategy->setSource($query, 'pixie');
    }
    
    /**
     * @covers ::setBatchData
     */
    public function testSetBatchData()
    {
        $query = $this->abstractMock('\PHPixie\Database\Type\SQL\Query\Type\Insert');
        
        $this->method($query, 'batchData', null, array(array('a', 'b'), array(
            array(1, 2),
            array(3, 4),
        )), 0);
        
        $this->strategy->setBatchData($query, array('a', 'b'), array(
            array(1, 2),
            array(3, 4),
        ));
    }
    
    protected function strategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Query\Strategy\SQL();
    }
}
