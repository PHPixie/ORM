<?php

namespace PHPixieTests\ORM\Steps\Step;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\In
 */
class InTest extends \PHPixieTests\ORM\Steps\StepTest
{
    protected $placeholder;
    protected $resultStep;
    
    public function setUp()
    {
        $this->placeholder = $this->quickMock('\PHPixie\Database\Conditions\Condition\Collection\Placeholder');
        $this->resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Query\Result');
        parent::setUp();
    }
    
    public function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\In($this->placeholder, 'test1', $this->resultStep, 'test2');
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $values = array(5);
        $container = $this->quickMock('\PHPixie\Database\Conditions\Builder\Operators\In');
        $this->method($this->resultStep, 'getField', $values, array('test2'), 0);
        $this->method($this->placeholder, 'container', $container, array(), 0);
        $this->method($container, 'addInOperatorCondition', null, array('test1', $values), 0);
        $this->step->execute();
    }
}