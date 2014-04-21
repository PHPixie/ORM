<?php

namespace \PHPixie\ORM\Planners\Planner\Pivot;

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
