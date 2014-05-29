<?php

namespace PHPixieTests\ORM\Steps\Step;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Update
 */
abstract class UpdateTest extends \PHPixieTests\ORM\Steps\StepTest
{
    protected $updateQuery;
    
    public function setUp()
    {
        $this->updateQuery = $this->query();
        parent::setUp();
    }
    
    protected function query()
    {
        return $this->abstractMock('\PHPixie\Database\Query\Type\Update', array('set'));
    }
}