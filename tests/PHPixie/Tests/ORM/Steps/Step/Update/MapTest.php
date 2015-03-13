<?php

namespace PHPixie\Tests\ORM\Steps\Step\Update;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Update\Map
 */
class MapTest extends \PHPixie\Tests\ORM\Steps\Step\UpdateTest
{
    protected $resultStep;
    
    public function setUp()
    {
        $this->resultStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Result', array('getFields'));
        parent::setUp();        
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $this->method($this->resultStep, 'getFields', array(
            array('pixie' => 'Trixie', 'test' => 5, 'spell' => null)
        ), array(array('pixie', 'test', 'spell')), 0);
        
        $this->method($this->updateQuery, 'set', null, array(array(
            'fairy' => 'Trixie',    
            'test2' => 5,
            'magic' => null
        )), 0);
        
        $this->step->execute();
    }
    
    /**
     * @covers ::execute
     */
    public function testExecuteException()
    {
        $this->method($this->resultStep, 'getFields', array(
            
        ));
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->execute();
    }    
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Update\Map($this->updateQuery, array(
            'fairy' => 'pixie',
            'test2' => 'test',
            'magic' => 'spell'
        ), $this->resultStep);
    }
}