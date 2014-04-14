<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Pivot;

class Pivot
{
    protected $connection;
    protected $pivot;
    
    public function __construct($connection, $pivot)
    {
        $this->connection = $connection;
        $this->pivot = $pivot;
    }
}
