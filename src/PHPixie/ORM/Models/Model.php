<?php

namespace PHPixie\ORM\Models;

abstract class Model
{
    protected $models;
    protected $config;
    protected $relationships;
    protected $wrapper;
    
    protected $modelConfigs = array();
    
    public function __construct($models, $config, $relationships)
    {
        $this->models        = $models;
        $this->config        = $config;
        $this->relationships = $relationships;
        $this->wrapper       = $this->models->wrapper();
    }
    
    public function config($modelName)
    {
        if(!array_key_exists($modelName, $this->modelConfigs)) {
            $configSlice = $this->models->modelConfigSlice($modelName);
            
            $modelType = $configSlice->get('type', 'database');
            if($modelType !== $this->type()) {
                throw new \PHPixie\ORM\Exception\Model("The type of '$modelName' model is '$modelType', expected {$this->type()}");
            }
            
            $this->modelConfigs[$modelName] = $this->buildConfig($modelName, $configSlice);
        }
        
        return $this->modelConfigs[$modelName];
    }
    
    abstract protected function buildConfig($modelName, $configSlice);
    
    abstract protected function type();
}