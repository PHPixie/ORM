<?php

namespace PHPixie\ORM;

class Builder
{
    protected $database;
    protected $config;

    protected $inflector;
    protected $drivers = array();
    protected $relationships = array();
    protected $relationshipMap;
    protected $repositories;
    protected $groupMapper;
    protected $conditions;
    protected $mapper;
    protected $loaders;
    protected $steps;
    protected $planners;
    protected $plans;

    public function __construct($database, $config)
    {
        $this->database = $database;
        $this->config = $config;
    }

    public function inflector()
    {
        if ($this->inflector === null)
            $this->inflector = $this->buildInflector();

        return $this->inflector;
    }
    
    public function maps(){}

    protected function buildInflector()
    {
        return new \PHPixie\ORM\Inflector();
    }

    public function relationships()
    {
    
    }
    
    public function configs()
    {
    
    }
    
    public function models()
    {
    
    }
    
    public function database()
    {
    
    }
    
    public function data()
    {
    
    }
    
    
    public function drivers()
    {
    
    }
    
    public function mappers()
    {
    
    }
    
    public function values()
    {
    
    }
    
    public function plans()
    {
    
    }
    
    public function steps()
    {
    
    }
    
    public function conditions()
    {
    
    }
    
    public function planners()
    {
    
    }
    
    public function loaders()
    {
    
    }
}
