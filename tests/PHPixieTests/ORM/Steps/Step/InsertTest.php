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
    
    protected function query()
    {
        return $this->quickMock('\PHPixie\Database\Query\Type\Insert', array('data', 'batchData'));
    }
}