<?php

namespace PHPixieTests\ORM\Steps\Step\Pivot\Insert;

/**
 * @coversDefaultClass \PHPixie\ORM\Steps\Step\Pivot\Insert\Unique
 */
class UniqueTest extends \PHPixieTests\ORM\Steps\Step\Pivot\InsertTest
{
    protected $selectQuery;
    protected $result;
    protected $connection;
    
    public function setUp()
    {
        $this->result = $this->quickMock('\PHPixie\Database\Result', array('getFields'));
        $this->connection = $this->quickMock('\PHPixie\Database\Connection', array());
        $this->selectQuery = $this->quickMock('\PHPixie\Database\Query\Type\Select', array('fields', 'where', 'execute', 'connection'));
        parent::setUp();
        $this->data = array(
            array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4),
        );
    }
    
    /**
     * @covers ::<protected>
     * @covers ::execute
     */
    public function testExecute()
    {
        $this->method($this->result, 'getFields', array(
            array('a' => 4, 'b' => 2, 'c' => 3, 'd' => 4),
            array('a' => 8, 'b' => 2, 'c' => 5, 'd' => 4),
        ));
        $this->method($this->selectQuery, 'fields', $this->selectQuery, 0, true, array($this->fields));
        $this->method($this->selectQuery, 'where', $this->selectQuery, 1, true, array('a', 'in', array(1,4,8)));
        $this->method($this->selectQuery, 'where', $this->selectQuery, 2, true, array('b', 'in', array(2,2,2)));
        $this->method($this->selectQuery, 'where', $this->selectQuery, 3, true, array('c', 'in', array(3,3,5)));
        $this->method($this->selectQuery, 'where', $this->selectQuery, 4, true, array('d', 'in', array(4,4,4)));
        $this->method($this->selectQuery, 'execute', $this->result);
        parent::testExecute();
    }
    
    /**
     * @covers ::usedConnections
     */
    public function testUsedConnections()
    {
        $this->method($this->selectQuery, 'connection', $this->connection);
        $this->assertEquals(array($this->connection), $this->step->usedConnections());
    }
    
    protected function getStep()
    {
        return new \PHPixie\ORM\Steps\Step\Pivot\Insert\Unique(
                                            $this->insertQuery,
                                            $this->queryPlanner,
                                            $this->fields,
                                            $this->cartesianStep,
                                            $this->selectQuery
                                        );
    }
}