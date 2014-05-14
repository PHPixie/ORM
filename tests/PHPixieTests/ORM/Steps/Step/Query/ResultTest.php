<?php

namespace PHPixieTests\ORM\Steps\Step\Query;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query
 */
abstract class ResultTest extends \PHPixieTests\ORM\Steps\Step\QueryTest
{

    protected $rows = array();
    
    public function setUp()
    {
        parent::setUp();
        for ($i = 0; $i < 3; $i++)
            $this->rows[] = new \stdClass();
        
        $this->rows[0]->name = 'pixie';
        $this->rows[1]->name = 'trixie';
    }
    
    /**
     * @covers ::execute
     * @covers ::<protected>
     */
    public function testExecuteException()
    {
        $this->method($this->query, 'execute', null, 0);
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
        $result = $this->setStepResult();
        $this->assertEquals($result, $this->step->result());
    }
    
    /**
     * @covers ::getField
     */
    public function testGetField()
    {
        $this->setStepResult();
        $this->assertEquals(array(
            'pixie',
            'trixie'
        ), $this->step->getField('name'));
    }
    
    /**
     * @covers ::getIterator
     * @covers ::<protected>
     */
    public function testIterator()
    {
        $result = $this->setStepResult();
        $this->method($this->query, 'execute', $result, 0);
        $this->step->execute();
        $this->iteratorTest();
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

        $result =  $this->databaseResultStub($this->rows);
        $this->method($this->query, 'execute', $result, 0);
        $this->step->execute();
        return $result;
    }
    
}