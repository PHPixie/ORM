<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Pivot;

class Side
{
    protected $collection;
    protected $repository;
    protected $pivotKey;

    public function __construct($collection, $repository, $pivotKey)
    {
        $this->collection = $collection;
        $this->repository = $repository;
        $this->pivotKey = $pivotKey;
    }

}
