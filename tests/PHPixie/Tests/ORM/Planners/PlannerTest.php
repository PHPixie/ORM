<?php

namespace PHPixie\Tests\ORM\Planners;

/**
 * @coversDefaultClass \PHPixie\ORM\Planners\Planner
 */
abstract class PlannerTest extends \PHPixie\Test\Testcase
{
    protected $planner;
    
    public function setUp()
    {
        $this->planner = $this->planner();
    }
    
    protected function assertStrategy($type, $class, $params = array())
    {
        $method = 'build'.ucfirst($type).'Strategy';
        
        $reflection = new \ReflectionClass(get_class($this->planner));
		$method = $reflection->getMethod($method);
		$method->setAccessible( true );
 		$strategy = $method->invokeArgs($this->planner, array());
        $this->assertInstance($strategy, $class, $params);
    }
    
    abstract protected function planner();
}