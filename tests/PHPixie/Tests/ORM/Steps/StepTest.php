<?php

namespace PHPixie\Tests\ORM\Steps;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step
 */
abstract class StepTest extends \PHPixie\Test\Testcase
{
    protected $connections;
    protected $step;
    
    public function setUp()
    {
        $this->connections = array(
            $this->getConnection(),
            $this->getConnection(),
        );
        
        $this->step = $this->getStep();
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        
    }
    
    /**
     * @covers ::usedConnections
     * @covers ::<protected>
     */
    public function testUsedConnections()
    {
        $this->assertEquals(array(), $this->step->usedConnections());
    }
    
    protected function getConnection()
    {
        return $this->abstractMock('\PHPixie\Database\Connection');
    }
    
    abstract protected function getStep();
    
}