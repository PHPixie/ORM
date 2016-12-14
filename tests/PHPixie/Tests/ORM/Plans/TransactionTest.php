<?php

namespace PHPixie\Tests\ORM\Plans;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Transaction
 */
class TransactionTest extends \PHPixie\Test\Testcase
{
    protected $connections = array();
    protected $transaction;
    
    public function setUp()
    {
        $this->connections = array(
            $this->connection(true),
            $this->connection(),
            $this->connection(true),
        );
        $this->transaction = new \PHPixie\ORM\Plans\Transaction($this->connections);
    }
    
    protected function connection($transactable = false)
    {
        $class = $transactable ? '\PHPixie\Database\Driver\PDO\Connection' : '\PHPixie\Database\Connection';
        return $this->quickMock($class);
    }
    
    /**
     * @covers ::begin
     * @covers ::commit
     * @covers ::<protected>
     */
    public function testTransaction()
    {
        $this->method($this->connections[0], 'inTransaction', false, array(), 0);
        $this->method($this->connections[2], 'inTransaction', false, array(), 0);
        
        $this->method($this->connections[0], 'beginTransaction', null, array(), 1);
        $this->method($this->connections[2], 'beginTransaction', null, array(), 1);
        
        $this->method($this->connections[0], 'commitTransaction', null, array(), 2);
        $this->method($this->connections[2], 'commitTransaction', null, array(), 2);
        
        $this->transaction->begin();
        $this->transaction->commit();
    }
    
    /**
     * @covers ::rollback
     * @covers ::<protected>
     */
    public function testRollbackTransaction()
    {
        $this->method($this->connections[0], 'inTransaction', false, array(), 0);
        $this->method($this->connections[2], 'inTransaction', false, array(), 0);
        
        $this->method($this->connections[0], 'beginTransaction', null, array(), 1);
        $this->method($this->connections[2], 'beginTransaction', null, array(), 1);
        
        $this->method($this->connections[0], 'rollbackTransaction', null, array(), 2);
        $this->method($this->connections[2], 'rollbackTransaction', null, array(), 2);
        
        $this->transaction->begin();
        $this->transaction->rollback();
    }
}