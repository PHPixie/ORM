<?php

namespace PHPixie\ORM;

class Builder
{
    protected $database;
    protected $configSlice;
    protected $wrappers;
    
    protected $instances = array();

    public function __construct($database, $configSlice, $wrappers = null)
    {
        $this->database    = $database;
        $this->configSlice = $configSlice;
        $this->wrappers    = $wrappers;
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
    
    protected function buildConditions()
    {
        return new Conditions($this);
    }
    
    protected function buildConfigs()
    {
        return new Configs();
    }
    
    protected function buildData()
    {
        return new Data();
    }
    
    protected function buildDatabase()
    {
        return new Database($this->database);
    }
    
    protected function buildDrivers()
    {
        return new Drivers($this);
    }
    
    protected function buildLoaders()
    {
        return new Loaders($this);
    }
    
    protected function buildMappers()
    {
        return new Mappers($this);
    }
    
    protected function buildMaps() {
        return new Maps(
            $this,
            $this->configSlice->slice('relationships')
        );
    }
    
    protected function buildModels()
    {
        return new Models(
            $this,
            $this->configSlice->slice('models'),
            $this->wrappers
        );
    }
    
    protected function buildPlanners()
    {
        return new Planners($this);
    }
    
    protected function buildPlans()
    {
        return new Plans();
    }
    
    protected function buildRelationships()
    {
        return new Relationships($this);
    }
    
    protected function buildRepositories()
    {
        return new Repositories($this->models());
    }
        
    protected function buildSteps()
    {
        return new Steps($this);
    }
    
    protected function buildValues()
    {
        return new Values();
    }
}
