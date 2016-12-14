<?php

namespace PHPixie\ORM\Plans;

class Transaction
{
    protected $connections;
    protected $transactions = array();
    
    public function __construct($connections)
    {
        $this->connections = $connections;
    }
    
    public function begin()
    {
        $transactions = array();
        foreach($this->getTransactable() as $connection) {
            if(!$connection->inTransaction()) {
                $connection->beginTransaction();
                $transactions[] = $connection;
            }
        }
        
        $this->transactions = $transactions;
    }

    public function commit()
    {
        foreach($this->transactions as $connection) {
            $connection->commitTransaction();
        }
        
        $this->transactions = array();
    }

    public function rollback()
    {
        foreach($this->transactions as $connection) {
            $connection->rollbackTransaction();
        }
        
        $this->transactions = array();
    }

    protected function getTransactable()
    {
        $transactable = array();
        foreach($this->connections as $connection) {
            if ($connection instanceof \PHPixie\Database\Connection\Transactable) {
                $transactable[] = $connection;
            }
        }

        return $transactable;
    }
}
