<?php

namespace PHPixie\ORM\Values;

class Update
{
    protected $values;
    protected $query;
    
    public function __construct($values, $query)
    {
        $this->values = $values;
        $this->query = $query;
    }
    
    public function set($values)
    {
        $this->builder = 
    }
}