<?php

namespace PHPixieTests\ORM\Steps;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step
 */
abstract class StepTest extends \PHPixieTests\AbstractORMTest
{
    protected $connections;
    protected $step;
    
    public function setUp()
    {
        $this->connections = array(
            $this->valueObject(),
            $this->valueObject(),
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
    
    abstract protected function getStep();
    
}