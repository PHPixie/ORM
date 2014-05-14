<?php

namespace \PHPixie\ORM\Planners\Planner\Update\Modifier;

class Data
{
    protected $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
}