<?php

namespace PHPixie\Tests\ORM\Steps\Step\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Result
 */
abstract class ResultTest extends \PHPixie\Tests\ORM\Steps\Step\QueryTest
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
        $this->setStepResult(false);
        $this->assertEquals($this->result, $this->step->result());
    }
    
    /**
     * @covers ::getField
     * @covers ::<protected>
     */
    public function testGetField()
    {
        $this->getFieldTest(false);
        $this->getFieldTest(true);
    }
    
    protected function getFieldTest($skipNulls)
    {
        $this->step = $this->getStep();
        $values = array(1, null, 2);
        $resultAt = $this->setStepResult();
        
        if($skipNulls) {
            $expected = array($values[0], $values[2]);
        }else{
            $expected = $values;
        }

        foreach($this->rows as $key => $row) {
            $this->method($this->result, 'getItemField', $values[$key], array($row, 'name'), $resultAt++);
        }
        $this->assertEquals($expected, $this->step->getField('name', $skipNulls));
    }
    
    /**
     * @covers ::getFields
     * @covers ::<protected>
     */
    public function testGetFields()
    {
        $values = array();
        foreach($this->rows as $row) {
            $values[] = (array) $row;
        }
        
        $resultAt = $this->setStepResult();
        
        foreach($this->rows as $key => $row) {
            foreach(array('name', 'magic') as $field) {
                $this->method($this->result, 'getItemField', $values[$key][$field], array($row, $field), $resultAt++);
            }
        }
        $this->assertEquals($values, $this->step->getFields(array('name', 'magic')));
    }

    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testIterator()
    {
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
    
    protected function setStepResult($withData = true)
    {
        $this->method($this->query, 'execute', $this->result, array(), 0);
        if($withData) {
            $at = $this->prepareResult();
        }else{
            $at = 0;
        }
        $this->step->execute();
        return $at;
    }
    
    abstract protected function prepareResult();
}