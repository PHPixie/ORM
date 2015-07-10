<?php

namespace PHPixie\Tests\ORM\Steps\Step\Query\Insert\Batch\Data;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data\Unique
 */
class UniqueTest extends \PHPixie\Tests\ORM\Steps\Step\Query\Insert\Batch\DataTest
{
    protected $dataStep;
    protected $selectQuery;
    
    public function setUp()
    {
        $this->dataStep = $this->abstractMock('\PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data\Unique');
        $this->selectQuery = $this->abstractMock('\PHPixie\Database\Driver\PDO\Query\Type\Select');
        
        parent::setUp();
    }
    
    /**
     * @covers::execute
     * @covers::data
     * @covers::<protected>
     */
    public function testExecute()
    {
        $fields = array('fairyId', 'flowerId');
        $data = array(
            array(1, 2),
            array(1, 3),
            array(3, 4),
            array(3, 5),
            array(5, 6),
        );
        $this->method($this->dataStep, 'fields', $fields, array(), 0);
        $this->method($this->dataStep, 'data', $data, array(), 1);
        
        $this->method($this->selectQuery, 'fields', null, array($fields), 0);
        foreach($fields as $key => $field) {
            $values = array();
            foreach($data as $row){
                $values[]=$row[$key];
            }
            $this->method($this->selectQuery, 'addInOperatorCondition', null, array(
                $field,
                $values
            ), $key+1);
        }
        
        $result = $this->abstractMock('\PHPixie\Database\Result');
        $this->method($this->selectQuery, 'execute', $result, array(), $key+2);
        
        $existing = array(
            array('fairyId' => 1, 'flowerId' => 3),
            array('fairyId' => 3, 'flowerId' => 5),
            array('fairyId' => 5, 'flowerId' => 6),
        );
        
        $this->method($result, 'getFields', $existing, array($fields), 0);
        $this->step->execute();
        
        $filtered = array($data[0], $data[2]);
        $this->assertSame($filtered, $this->step->data());
    }
    
    /**
     * @covers::fields
     * @covers::<protected>
     */
    public function testFields()
    {
        $fields = array('fairyId', 'flowerId');
        $this->method($this->dataStep, 'fields', $fields, array(), 0);
        
        $this->assertSame($fields, $this->step->fields());
    }
    
    /**
     * @covers::data
     * @covers::<protected>
     */
    public function testDataException()
    {
        $this->setExpectedException('\PHPixie\ORM\Exception\Plan');
        $this->step->data();
    }
    
    /**
     * @covers ::usedConnections
     * @covers ::<protected>
     */
    public function testUsedConnections()
    {
        $connection = $this->abstractMock('\PHPixie\Database\Connection');
        $this->method($this->selectQuery, 'connection', $connection, array(), 0);
        
        $this->assertEquals(array($connection), $this->step->usedConnections());
    }
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Query\Insert\Batch\Data\Unique(
            $this->dataStep,
            $this->selectQuery
        );
    }
}