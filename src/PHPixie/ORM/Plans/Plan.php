<?php

namespace PHPixie\ORM\Plans;

abstract class Plan
{
    protected $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    public function execute()
    {
        $steps = $this->steps();
        $connections = $this->usedConnections();
        $this->transaction->begin($connections);

        try {
            foreach($steps as $step)
                $step->execute();
            $this->transaction->commit($connections);

        } catch (\Exception $exception) {
            $this->transaction->rollback($connections);
            throw $exception;
        }
    }

    public function usedConnections()
    {
        $connections = array();
        foreach ($this->steps() as $step) {
            foreach($step->usedConnections() as $connection)
                if (!in_array($connection, $connections, true))
                    $connections[] = $connection;
        }

        return $connections;
    }

    abstract public function steps();
}
