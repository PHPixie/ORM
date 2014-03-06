<?php

namespace PHPixie\ORM\Relationship;

class Registry
{
    protected $repositories = array();

    public function __construct($orm, $config)
    {
        foreach (array_keys($config->data()) as $modelName) {
            $modelConfig = $this->config->slice($modelName);
            $repository = $orm->buildRepository($modelName, $modelConfig);
            $this->repositories[$modelName] = $repository;
        }
    }

    public function get($modelName)
    {
        return $this->repositories[$modelName];
    }
}
