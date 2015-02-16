<?php

namespace PHPixie\ORM;

class Builder
{
    protected $database;
    protected $config;
    protected $wrappers;
    
    protected $instances = array();

    public function __construct($database, $config, $wrappers = null)
    {
        $this->database = $database;
        $this->config   = $config;
        $this->wrappers = $wrappers;
    }
    
    public function conditions()
    {
        return $this->instance('conditions');
    }
    
    public function configs()
    {
        return $this->instance('configs');
    }
    
    public function data()
    {
        return $this->instance('data');
    }
    
    public function database()
    {
        return $this->instance('database');
    }
    
    public function drivers()
    {
        return $this->instance('drivers');
    }
    
    public function loaders()
    {
        return $this->instance('loaders');
    }
    
    public function mappers()
    {
        return $this->instance('mappers');
    }
    
    public function maps() {
        return $this->instance('maps');
    }
    
    public function models()
    {
        return $this->instance('models');
    }
    
    public function planners()
    {
        return $this->instance('planners');
    }
    
    public function plans()
    {
        return $this->instance('plans');
    }
    
    public function relationships()
    {
        return $this->instance('relationships');
    }
    
    public function repositories()
    {
        return $this->instance('repositories');
    }
        
    public function steps()
    {
        return $this->instance('steps');
    }
    
    public function values()
    {
        return $this->instance('values');
    }
    
    protected function instance($name)
    {
        if(!array_key_exists($name, $this->instances)) {
            $method = 'build'.ucfirst($name);
            $this->instances[$name] = $this->$method();
        }
        
        return $this->instances[$name];
    }
    
    public function buildConditions()
    {
        return new Conditions(
            $this->maps()
        );
    }
    
    public function buildConfigs()
    {
        return new Configs();
    }
    
    public function buildData()
    {
        return $this->instance('data');
    }
    
    public function buildDatabase()
    {
        return $this->instance('database');
    }
    
    public function bildDrivers()
    {
        return $this->instance('drivers');
    }
    
    public function buildLoaders()
    {
        return $this->instance('loaders');
    }
    
    public function buildMappers()
    {
        return $this->instance('mappers');
    }
    
    public function builMaps() {
        return $this->instance('maps');
    }
    
    public function buildModels()
    {
        return $this->instance('models');
    }
    
    public function buildPlanners()
    {
        return $this->instance('planners');
    }
    
    public function buildPlans()
    {
        return $this->instance('plans');
    }
    
    public function buildRelationships()
    {
        return $this->instance('relationships');
    }
    
    public function buildRepositories()
    {
        return $this->instance('repositories');
    }
        
    public function buildSteps()
    {
        return $this->instance('steps');
    }
    
    public function buildValues()
    {
        return $this->instance('values');
    }
}
