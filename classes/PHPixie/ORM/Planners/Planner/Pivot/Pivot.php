<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Pivot;

class Pivot
{
    protected $pivotConnection;
    protected $pivot;

    public function __construct($pivotConnection, $pivot)
    {
        $this->pivotConnection = $pivotConnection;
        $this->pivot = $pivot;
    }

}
