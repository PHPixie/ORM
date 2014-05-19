<?php

namespace PHPixieTests\ORM\Planners\Planner\Strategy;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Strategy\Query
 */
class QueryTest extends \PHPixieTests\ORM\Planners\Planner\StrategyTest
{

    /**
     * @covers ::setSource
     */
    public function testSetSource()
    {
        $query = $this->quickMock('\PHPixie\Database\Driver\PDO\Query', array('table'));
        $this->method($query, 'table', null, 0, true, array('pixie'));
        $this->assertEquals($query, $this->planner->setSource($query, 'pixie'));
        
        $query = $this->quickMock('\PHPixie\Database\Driver\Mongo\Query', array('collection'));
        $this->method($query, 'collection', null, 0, true, array('pixie'));
        $this->assertEquals($query, $this->planner->setSource($query, 'pixie'));
    }
    
    protected function getPlanner()
    {
        return new \PHPixie\ORM\Planners\Planner\Strategy\Query();
    }
}