<?php

namespace PHPixieTests\ORM\Steps\Step\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Result
 */
abstract class ResultTest extends \PHPixieTests\ORM\Steps\Step\QueryTest
{

    protected $result;
    protected $rows = array();
    
    public function setUp()
    {
        for ($i = 0; $i < 3; $i++) {
            $item = new \stdClass();
            $item->name = 'fairy'.$i;
            $item->magic = 'spell'.$i;
            $this->rows[]=$item;
        }
        $this->result = $this->abstractMock('\PHPixie\Database\Result');
        $this->method($this->result, 'asArray', $this->rows, array());
        parent::setUp();
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecuteException()
    {
        $this->method($this->query, 'execute', null, array(), 0);
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->execute();
    }
    
    /**
     * @covers ::result
     * @covers ::<protected>
     */
    public function testResultException()
    {
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->result();
    }
    
    /**
     * @covers ::execute
     * @covers ::result
     * @covers ::<protected>
     */
    public function testExecute()
    {
        $this->setStepResult();
        $this->assertEquals($this->result, $this->step->result());
    }
    
    /**
     * @covers ::getField
     */
    public function testGetField()
    {
        $this->setStepResult();
        $this->method($this->result, 'getField', array(5), array('name', true), 0);
        $this->assertEquals(array(5), $this->step->getField('name'));
    }
    
    /**
     * @covers ::getFields
     */
    public function testGetFields()
    {
        $fields = array('pixie', 'trixie');
        $this->setStepResult();
        $this->method($this->result, 'getFields', array(5), array($fields), 0);
        $this->assertEquals(array(5), $this->step->getFields($fields));
    }
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testIterator()
    {
        $this->prepareIterator();
        $this->setStepResult();
        $this->iteratorTest();
    }
    
    /**
     * @covers ::asArray
     * @covers ::<protected>
     */
    public function testAsArray()
    {
        $this->setStepResult();
        $this->assertSame($this->rows, $this->step->asArray());
    }
    
    protected function iteratorTest()
    {
        $rows = array();
        foreach($this->step as $row) {
            $rows[] = $row;
        }
        $this->assertEquals($this->rows, $rows);
    }
    
    protected function setStepResult()
    {
        $this->method($this->query, 'execute', $this->result, array(), 0);
        $this->step->execute();
    }
    
    protected function prepareIterator()
    {
        $rows = $this->rows;
        $pos = 0;
        $this->result
            ->expects($this->any())
            ->method('valid')
            ->will($this->returnCallback(function() use(&$pos){
                return $pos < 3;
            }));
        
        $this->result
            ->expects($this->any())
            ->method('next')
            ->will($this->returnCallback(function() use(&$pos){
                $pos++;
            }));
        
        $this->result
            ->expects($this->any())
            ->method('current')
            ->will($this->returnCallback(function() use(&$pos, $rows){
                return $rows[$pos];
            }));
    }
    
}