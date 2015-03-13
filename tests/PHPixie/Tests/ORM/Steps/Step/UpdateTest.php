<?php

namespace PHPixie\Tests\ORM\Steps\Step;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Update
 */
abstract class UpdateTest extends \PHPixie\Tests\ORM\Steps\StepTest
{
    protected $updateQuery;
    
    /**
     * @covers \PHPixie\ORM\Steps\Step\Update::__construct
     * @covers ::__construct
     * @covers ::<protected>
     */
    public function testConstruct()
    {
    
    }
    
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