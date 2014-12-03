<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Update
 */
class UpdateTest extends \PHPixieTests\ORM\Planners\PlannerTest
{

    /**
     * @covers ::<protected>
     * @covers ::result
     */
    public function testResult()
    {
        $updateQuery = $this->abstractMock('\PHPixie\Database\Query\Type\Update');
        $resultStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result');
        $plan = $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
        
        $map = array(
                        'a' => 't',
                        'b' => 't',
                        'c' => 't2'
                    );
        
        $this->prepareResultTest($updateQuery, $map, $resultStep, $plan);
        $this->planner->result($updateQuery, $map, $resultStep, $plan);
        
    }
    
    /**
     * @covers ::<protected>
     * @covers ::subquery
     */
    public function testSubquery()
    {
        $updateQuery = $this->abstractMock('\PHPixie\Database\Query\Type\Update');
        $subquery = $this->abstractMock('\PHPixie\Database\Query\Type\Select');
        $resultStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result');
        $plan = $this->abstractMock('\PHPixie\ORM\Plans\Plan\Steps');
        
        $map = array(
                        'a' => 't',
                        'b' => 't',
                        'c' => 't2'
                    );
        
        $this->method($subquery, 'fields', null, array(array('t', 't2')), 0);
        $this->method($this->steps, 'result', $resultStep, array($subquery), 0);
        $this->method($plan, 'add', null, array($resultStep), 0);
        $this->prepareResultTest($updateQuery, $map, $resultStep, $plan, 1, 1);
        $this->planner->subquery($updateQuery, $map, $subquery, $plan);
        
    }
    
    
    protected function prepareResultTest($updateQuery, $map, $resultStep, $plan, $stepsIndex = 0, $planIndex = 0)
    {
        $updateStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Update\Map');
        $this->method($this->steps, 'updateMap', $updateStep, array($updateQuery, $map, $resultStep), $stepsIndex);
        $this->method($plan, 'add', null, array($updateStep), $planIndex);   
    }
    
    protected function getPlanner()
    {
        return new \PHPixie\ORM\Planners\Planner\Update($this->steps);
    }
}
