<?php

namespace PHPixie\ORM\Repositories;

abstract class Overrides
{
    protected $repositoryMethods = array();
    
    public function has($modelName)
    {
        return array_key_exist($modelName, $this->repositoryMethods);
    }
    
    public function get($modelName)
    {
        $method = $modelName, $this->repositoryMethods
    }
    
} 