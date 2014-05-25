<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Pivot
 */
class PivotTest extends \PHPixieTests\ORM\Planners\PlannerTest
{
    /**
     * @covers ::pivot
     * @covers ::<protected>
     */
    public function testPivot()
    {
        $connection = $this->quickMock('\PHPixie\Database\Connection');
        $pivot = $this->planner->pivot($connection, 'fairies');
        $this->assertInstanceOf('\PHPixie\ORM\Planners\Planner\Pivot\Pivot', $pivot);
        $this->assertEquals($connection, $pivot->connection());
        $this->assertEquals('fairies', $pivot->source());
    }
    
    /**
     * @covers ::side
     * @covers ::<protected>
     */
    public function testSide()
    {
        $items = array(5);
        $repository = $this->quickMock('\PHPixie\ORM\Model\Repository');
        $side = $this->planner->side($items, $repository, 'fairy');
        $this->assertInstanceOf('\PHPixie\ORM\Planners\Planner\Pivot\Side', $side);
        $this->assertEquals($items, $side->items());
        $this->assertEquals($repository, $side->repository());
        $this->assertEquals('fairy', $side->pivotKey());
    }
    
    protected function getPlanner()
    {
        return new \PHPixie\ORM\Planners\Planner\Pivot($this->strategies);
    }
}