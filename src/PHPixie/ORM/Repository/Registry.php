<?php

namespace PHPixie\ORM\Relationship;

class Registry
{
    protected $config;
    protected $repositories = array();
    
    public function __construct($orm, $config)
    {
        $this->config = $config;
    }

    public function get($modelName)
    {
        if (!array_key_exists($modelName, $this->repositories)){
            $this->repositories[$modelName] = $this->orm->buildRepository($modelName, $this->config->slice($modelName));
        
        return $this->repositories[$modelName];
    }
}
