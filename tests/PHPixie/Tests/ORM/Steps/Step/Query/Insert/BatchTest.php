<?php

namespace PHPixie\Tests\ORM\Steps\Step\Query\Insert;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Insert\Batch
 */
class BatchTest extends \PHPixie\Tests\ORM\Steps\Step\Query\InsertTest
{
    protected $queryPlanner;
    protected $dataStep;
    
    public function setUp()
    {
        $this->queryPlanner = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query');
        $this->dataStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data');
        
        parent::setUp();
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $data = array(
            array(1, 2),
            array(3, 4),
        );
        $fields = array('fairyId', 'flowerId');
        
        $this->method($this->dataStep, 'data', $data, array(), 0);
        $this->method($this->dataStep, 'fields', $fields, array(), 1);
        
        $this->method($this->queryPlanner, 'setBatchData', null, array($this->query, $fields, $data), 0);
        
        $this->step->execute();
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecuteEmpty()
    {
        $this->method($this->dataStep, 'data', array(), array(), 0);
        $this->step->execute();
    }
    
    public function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Query\Insert\Batch(
            $this->queryPlanner,
            $this->query,
            $this->dataStep
        );
    }
}