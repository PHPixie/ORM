<?php

namespace PHPixie\ORM\Plans;

abstract class Plan
{
    protected $plans;
    
    public function __construct($plans)
    {
        $this->plans = $plans;
    }

    public function execute()
    {
        $steps = $this->steps();
        $connections = $this->usedConnections();
        
        $transaction = $this->plans->transaction($connections);
        $transaction->begin();

        try {
            foreach($steps as $step) {
                $step->execute();
            }
            $transaction->commit();

        } catch (\Exception $exception) {
            $transaction->rollback();
            throw $exception;
        }
    }

    public function usedConnections()
    {
        $connections = array();
        foreach ($this->steps() as $step) {
            foreach($step->usedConnections() as $connection) {
                if (!in_array($connection, $connections, true)) {
                    $connections[] = $connection;
                }
            }
        }

        return $connections;
    }

    abstract public function steps();
}
