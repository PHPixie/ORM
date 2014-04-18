<?php

namespace PHPixie\ORM;

class Builder
{
    protected $conditions;
    protected $drivers;
    protected $repositories;
    protected $relationships = array();
    protected $relationshipMap;
    protected $groupMapper;
    protected $mapper;
    protected $loaders;
    protected $planners;
    protected $inflector;

    
    public function conditions()
    {
        if ($this->conditions === null)
            $this->conditions = $this->buildConditions();

        return $this->conditions;
    }

    protected function buildConditions()
    {
        return new \PHPixie\ORM\Conditions();
    }
    
    public function inflector()
    {
        if ($this->inflector === null)
            $this->inflector = $this->buildInflector();

        return $this->inflector;
    }

    protected function buildInflector()
    {
        return new \PHPixie\ORM\Inflector();
    }
    
    public function driver($name)
    {
        if (!isset($this->drivers[$name]))
            $this->drivers[$name] = $this->buildDriver($name);

        return $this->drivers[$name];
    }

    protected function buildDriver($name)
    {
        $class = '\PHPixie\ORM\Driver\\'.$name;
        return new $class($this);
    }
    
    public function repositories()
    {
        if($this->repositories === null)
            $this->repositories = $this->buildRepositories();

        return $this->repositories;
    }

    protected function buildRepositories()
    {
        $modelsConfig = $this->config->slice('models');
        return new \PHPixie\ORM\Repositories($this, $modelsConfig);
    }
    
    
    public function relationship($name)
    {
        if (!isset($this->relationships[$name]))
            $this->relationships[$name] = $this->buildRelationship($name);

        return $this->relationships[$name];
    }

    public function buildRelationship($name)
    {
        $class = '\PHPixie\ORM\Relationships\Relationship\\'.$name;
        return new $class($this);
    }

    public function relationshipMap()
    {
        if ($this->relationshipMap === null)
            $this->relationshipMap = $this->buildRelationshipMap();
        
        return $this->relationshipMap;
    }

    public function buildPropertyBuilder()
    {
        return new \PHPixie\ORM\Properties\Builder($this, $this->relationshipMap());
    }

    public function groupMapper()
    {
        if($this->groupMapper === null)
            $this->groupMapper = $this->buildGroupMapper();

        return $this->groupMapper;
    }

    public function buildGroupMapper()
    {
        return \PHPixie\ORM\Mapper\Group($this);
    }

    public function mapper()
    {
        if($this->mapper === null)
            $this->mapper = $this->buildMapper();

        return $this->mapper;
    }

    public function buildMapper()
    {
        return \PHPixie\ORM\Mapper($this, $this->loaders(), $this->groupMapper());
    }
    
    public function loaders()
    {
        if ($this->loaders === null)
            $this->loaders = $this->buildLoaders();
        return $this->loaders;
    }
    
    public function buildLoaders()
    {
        return new \PHPixie\ORM\Loaders();
    }
    
    public function planners()
    {
        if ($this->planners === null)
            $this->planners = $this->buildPlanners();
        return $this->planners;
    }
    
    public function buildPlanners()
    {
        return new \PHPixie\ORM\Planners();
    }
    
    public function plan()
    {
        return new \PHPixie\ORM\Plan();
    }
    
    public function loaderPlan()
    {
        return new \PHPixie\ORM\Plan\Loader();
    }
    
    p
}