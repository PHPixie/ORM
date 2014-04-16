<?php

namespace PHPixie\ORM;

class Repositories
{
    protected $config;
    protected $repositories = array();
    
    public function __construct($orm, $config)
    {
        $this->config = $config;
    }

    public function get($name)
    {
        if (!array_key_exists($name, $this->repositories)){
            $this->repositories[$name] = $this->orm->buildRepository($name, $this->config->slice($modelName));
        
        return $this->repositories[$name];
    }
}
