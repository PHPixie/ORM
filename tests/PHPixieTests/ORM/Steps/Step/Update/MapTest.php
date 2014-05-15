<?php

namespace PHPixieTests\ORM\Steps\Step\Update;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Update\Map
 */
class MapTest extends \PHPixieTests\ORM\Steps\Step\UpdateTest
{
    protected $result;
    protected $resultStep;
    
    public function setUp()
    {
        $this->result = $this->quickMock('\PHPixie\Database\Result', array('asArray'));
        $this->resultStep = $this->quickMock('\PHPixie\ORM\Steps\Step\Result', array('result'));
        $this->method($this->resultStep, 'result', $this->result);
        parent::setUp();        
    }
    
    /**
     * @covers ::execute
     */
    public function testExecute()
    {
        $this->method($this->result, 'asArray', array(
            array('pixie' => 'Trixie', 'test' => 5)
        ));
        
        $this->method($this->updateQuery, 'set', array(
            'fairy' => 'Trixie',    
            'test2' => 5,
            'magic' => null
        ), null);
        
        $this->step->execute();
    }
    
    /**
     * @covers ::execute
     */
    public function testExecuteException()
    {
        $this->method($this->result, 'asArray', array(
            
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