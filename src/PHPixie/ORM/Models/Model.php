<?php

namespace PHPixie\ORM\Models;

abstract class Model
{
    protected $models;
    protected $configs;
    protected $wrappers;
    
    protected $modelConfigs = array();
    protected $wrapped = array();
    
    public function __construct($models, $configs)
    {
        $this->models        = $models;
        $this->configs       = $configs;
        $this->wrappers      = $this->models->wrappers();
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
    
    protected function hasWrapper($type, $modelName)
    {
        if(!array_key_exists($type, $this->wrapped)) {
            if($this->wrappers !== null) {
                $this->wrapped[$type] = array_fill_keys($this->wrappers->$type(), true);
            }else{
                $this->wrapped[$type] = array();
            }
        }
        
        return array_key_exists($modelName, $this->wrapped[$type]);
    }
    
    abstract protected function buildConfig($modelName, $configSlice);
    
    abstract public function type();
}