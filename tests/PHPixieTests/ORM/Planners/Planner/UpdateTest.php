<?php

namespace PHPixieTests\ORM\Planners\Planner;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner\Update
 */
class UpdateTest extends \PHPixieTests\AbstractORMTest
{
    protected $steps;
    
    protected $update;
    
    public function setUp()
    {
        $this->steps = $this->quickMock('\PHPixie\ORM\Steps');
        $this->update = new \PHPixie\ORM\Planners\Planner\Update($this->steps);
    }
    
    /**
     * @covers ::<protected>
     * @covers ::__construct
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::<protected>
     * @covers ::result
     */
    public function testResult()
    {
        $updateQuery = $this->getUpdateQuery();
        $resultStep  = $this->getResultStep();
        $plan        = $this->getStepsPlan();
        
        $map = array(
            'a' => 't',
            'b' => 't',
            'c' => 't2'
        );
        
        $this->prepareResultTest($updateQuery, $map, $resultStep, $plan);
        $this->update->result($updateQuery, $map, $resultStep, $plan);
        
    }
    
    /**
     * @covers ::<protected>
     * @covers ::subquery
     */
    public function testSubquery()
    {
        $updateQuery = $this->getUpdateQuery();
        $subquery    = $this->getSelectQuery();
        $resultStep  = $this->getResultStep();
        $plan        = $this->getStepsPlan();
        
        $map = array(
            'a' => 't',
            'b' => 't',
            'c' => 't2'
        );
        
        $this->method($subquery, 'fields', null, array(array('t', 't2')), 0);
        $this->method($this->steps, 'iteratorResult', $resultStep, array($subquery), 0);
        $this->method($plan, 'add', null, array($resultStep), 0);
        $this->prepareResultTest($updateQuery, $map, $resultStep, $plan, 1, 1);
        $this->update->subquery($updateQuery, $map, $subquery, $plan);
        
    }
    
    
    protected function prepareResultTest($updateQuery, $map, $resultStep, $plan, $stepsIndex = 0, $planIndex = 0)
    {
        $updateStep = $this->getUpdateMapStep();
        $this->method($this->steps, 'updateMap', $updateStep, array($updateQuery, $map, $resultStep), $stepsIndex);
        $this->method($plan, 'add', null, array($updateStep), $planIndex);   
    }
    
    protected function getUpdateQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\Update');
    }
    
    protected function getSelectQuery()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\Select');
    }
    
    protected function getResultStep()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
    }
    
    protected function getStepsPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Steps');
    }
    
    protected function getUpdateMapStep()
    {
        return $this->quickMock('\PHPixie\ORM\Steps\Step\Update\Map');
    }
}
