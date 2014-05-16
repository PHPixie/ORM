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
     * @covers ::<protected>
     * @covers ::execute
     */
    public function testExecute()
    {
        $this->method($this->queryPlanner, 'setBatchData', null, null, true, array(
                                                                            $this->insertQuery,
                                                                            $this->fields,
                                                                            $this->data)
                                                                        );
        $this->step->execute();
    }
}