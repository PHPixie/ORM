<?php

namespace PHPixie\ORM\Planners\Planner\Pivot;

class Pivot
{
    protected $connection;
    protected $source;

    public function __construct($connection, $source)
    {
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
}
