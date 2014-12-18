<?php

namespace PHPixie\ORM\Models;

class Model
{
    protected $wrappers;
    
    public function __construct($wrappers)
    {
        $this->wrappers = $wrappers;
    }
    
    public abstract function enity($modelName, $data);
}