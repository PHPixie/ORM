<?php

namespace PHPixie;

class ORM
{
    protected $builder;
    
    public function __construct($database, $configSlice, $wrappers = null)
    {
        $this->builder = new ORM\Builder($database, $configSlice, $wrappers);
    }
    
    public function get($modelName)
    {
        return $this->builder->models()->database()->repository($modelName);
    }
}
