<?php

namespace PHPixieTests\ORM\Steps\Step;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\In
 */
class InTest extends \PHPixieTests\ORM\Steps\StepTest
{
    protected $builder;
    protected $placeholder;
    protected $resultStep;
    
    public function setUp()
    {
        $this->builder = $this->quickMock('\PHPixie\Database\Conditions\Builder', array('_and'));
        $this->placeholder = $this->quickMock('\PHPixie\Database\Conditions\Condition\Placeholder', array('builder'));
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
        $this->method($this->resultStep, 'getField', $values, array('test2'), 0);
        $this->method($this->placeholder, 'builder', $this->builder, array(), 0);
        $this->method($this->builder, '_and', null, array('test1', 'in', $values), 0);
        $this->step->execute();
    }
}