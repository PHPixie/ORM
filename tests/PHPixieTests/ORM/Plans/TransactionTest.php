<?php

namespace PHPixieTests\ORM\Plans;

/**
 * @coversDefaultClass \PHPixie\ORM\Plans\Transaction
 */
class TransactionTest extends \PHPixieTests\AbstractORMTest
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
        $this->transaction = new \PHPixie\ORM\Plans\Transaction;
    }
    
    protected function connection($transactable = false)
    {
        $class = $transactable ? '\PHPixie\Database\Connection\Transactable' : '\PHPixie\Database\Connection';
        return $this->quickMock($class, array(
            'beginTransaction',
            'commitTransaction',
            'rollbackTransaction',
        ));
    }
    
    /**
     * @covers ::begin
     * @covers ::<protected>
     */
    public function testBeginTransaction()
    {
        $this->transactionTest('begin');
    }
    
    /**
     * @covers ::commit
     * @covers ::<protected>
     */
    public function testCommitTransaction()
    {
        $this->transactionTest('commit');
    }
    
    /**
     * @covers ::rollback
     * @covers ::<protected>
     */
    public function testRollbackTransaction()
    {
        $this->transactionTest('rollback');
    }
    
    protected function transactionTest($method)
    {
        $connectionMethod = $method.'Transaction';
        $this->method($this->connections[0], $connectionMethod, null, 0);
        $this->connections[1]
                ->expects($this->never())
                ->method($connectionMethod);
        $this->method($this->connections[2], $connectionMethod, null, 0);
        $this->transaction->$method($this->connections);
    }
}