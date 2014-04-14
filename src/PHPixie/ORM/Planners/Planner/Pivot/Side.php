<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Pivot;

class Side
{
    public $items;
    public $repository;
    public $pivotKey;

    public function __construct($items, $repository, $pivotKey)
    {
        $this->items = $items;
        $this->repository = $repository;
        $this->pivotKey = $pivotKey;
    }

}
