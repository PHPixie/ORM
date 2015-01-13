<?php

namespace PHPixieTests\ORM\Steps\Step;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Insert
 */
abstract class InsertTest extends \PHPixieTests\ORM\Steps\StepTest
{
    protected $insertQuery;
    
    public function setUp()
    {
        $this->insertQuery = $this->query();
        parent::setUp();
    }
    
    /**
     * @covers \PHPixie\ORM\Steps\Step\Insert::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
    protected function query()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\Insert', array('data', 'batchData'));
    }
}