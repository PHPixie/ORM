<?php

namespace PHPixie\ORM\Plans;

class Transaction
{
    public function begin($connections)
    {
        foreach($this->getTransactable($connections) as $connection)
            $connection->beginTransaction();
    }

    public function commit($connections)
    {
        foreach($this->getTransactable($connections) as $connection)
            $connection->commitTransaction();
    }

    public function rollback($connections)
    {
        foreach($this->getTransactable($connections) as $connection)
            $connection->rollbackTransaction();
    }

    protected function getTransactable($connections)
    {
        $transactable = array();
        foreach($connections as $connection)
            if ($connection instanceof \PHPixie\Database\Connection\Transactable)
                $transactable[] = $connection;

        return $transactable;
    }
}
