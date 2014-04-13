<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Pivot;

class Side
{
    public $collection;
    public $repository;
    public $pivotKey;

    public function __construct($collection, $repository, $pivotKey)
    {
        $this->collection = $collection;
        $this->repository = $repository;
        $this->pivotKey = $pivotKey;
    }

}
