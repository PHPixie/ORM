<?php

namespace PHPixie\ORM;

class Repositories
{
    protected $models;
    
    public function __construct($models)
    {
        $this->models = $models;
    }

    public function get($name)
    {
        return $this->models->database()->repository($name);
    }
}
