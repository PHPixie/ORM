<?php

namespace PHPixie\Tests\ORM\Steps\Step;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\In
 */
class InTest extends \PHPixie\Tests\ORM\Steps\StepTest
{
    protected $container;
    protected $resultStep;
    
    public function setUp()
    {
        $this->container  = $this->abstractMock('\PHPixie\Database\Conditions\Builder\Operators\In');
        $this->resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
        parent::setUp();
    }
    
    public function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\In($this->container, 'test1', $this->resultStep, 'test2');
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $values = array(5);
        $this->method($this->resultStep, 'getField', $values, array('test2'), 0);
        $this->method($this->container, 'addInOperatorCondition', null, array('test1', $values), 0);
        $this->step->execute();
    }
}