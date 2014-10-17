<?php

namespace PHPixie\ORM\Planners\Planner\Pivot;

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
    
    public function items()
    {
        return $this->items;
    }
    
    public function repository()
    {
        return $this->repository;
    }
    
    public function pivotKey()
    {
        return $this->pivotKey;
    }
    
    public function connection()
    {
        return $this->repository->connection();
    }
}
