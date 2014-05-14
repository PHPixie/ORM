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
        $this->placeholder = $this->quickMock('\PHPixie\Database\Conditions\Condition\Placeholder', array('where'));
        $this->resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Result', array('getField'));
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
        $values = $this->valueObject();
        $this->method($this->resultStep, 'getField', $values, null, true, array('test2'));
        $this->method($this->placeholder, 'where', null, null, true, array('test1', 'in', $values));
        $this->step->execute();
    }
}