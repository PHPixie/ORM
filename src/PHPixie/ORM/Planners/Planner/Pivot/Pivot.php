<?php

namespace PHPixie\ORM\Planners\Planner\Pivot;

class Pivot
{
    protected $queryPlanner;
    protected $connection;
    protected $source;

    public function __construct($queryPlanner, $connection, $source)
    {
        $this->queryPlanner = $queryPlanner;
        $this->connection = $connection;
        $this->source = $source;
    }
    
    public function source()
    {
        return $this->source;
    }
    
    public function connection()
    {
        return $this->connection;
    }
    
    public function databaseSelectQuery()
    {
        $query = $this->connection->selectQuery();
        $this->queryPlanner->setSource($query, $this->source);
        return $query;
    }
    
    public function databaseInsertQuery()
    {
        $query = $this->connection->insertQuery();
        $this->queryPlanner->setSource($query, $this->source);
        return $query;
    }
    
    public function databaseDeleteQuery()
    {
        $query = $this->connection->deleteQuery();
        $this->queryPlanner->setSource($query, $this->source);
        return $query;
    }
}
