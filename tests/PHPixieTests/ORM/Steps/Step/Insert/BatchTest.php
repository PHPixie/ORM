<?php

namespace PHPixieTests\ORM\Steps\Step\Insert;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Insert\Batch
 */
abstract class BatchTest extends \PHPixieTests\ORM\Steps\Step\InsertTest
{
    protected $queryPlanner;
    protected $fields;
    protected $data;
    
    public function setUp()
    {
        $this->queryPlanner = $this->quickMock('\PHPixie\ORM\Planners\Planner\Query', array('setBatchData'));
        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Steps\Step\Insert::__construct
     * @covers \PHPixie\ORM\Steps\Step\Insert\Batch::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $this->method($this->queryPlanner, 'setBatchData', null, array(
            $this->insertQuery,
            $this->fields,
            $this->data
        ), 0);
        $this->step->execute();
    }
}