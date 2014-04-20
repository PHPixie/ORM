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
        $class = '\PHPixie\ORM\Drivers\Driver\\'.$name;
        return new $class($this);
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
    
    public function relationshipMap()
    {
        if ($this->relationshipMap === null)
            $this->relationshipMap = $this->buildRelationshipMap();
        
        return $this->relationshipMap;
    }

    public function buildRelationshipMap()
    {
        return new \PHPixie\ORM\Relationships\Map($this, $this->config->slice('relationships'));
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

    public function cascadeMapper()
    {
        if($this->cascadeMapper === null)
            $this->cascadeMapper = $this->buildCascadeMapper();

        return $this->cascadeMapper;
    }

    public function buildCascadeMapper()
    {
		$repositoryRegistry = $this->repositoryRegistry();
		$relationshipMap = $this->relationshipMap();
		$planners = $this->planners();
		$steps = $this->steps();
		$optimizer = $this->buildOptimizer();
        return \PHPixie\ORM\Mapper\Cascade($this, $repositoryRegistry, $relationshipMap, $planners, $steps, $optimizer);
    }
	
	protected function buildOptimizer()
	{
		$optimizerMerger = $this->buildGroupOptimizerMerger();
		return new \PHPixie\ORM\Mapper\Group\Optimizer($this, $optimizerMerger);
	}
	
	protected function buildGroupOptimizerMerger()
	{
		return new \PHPixie\ORM\Mapper\Group\Optimizer\Merger($this);
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

    public function steps()
    {
        if ($this->steps === null)
            $this->steps = $this->buildSteps();
        return $this->steps;
    }
    
    public function buildSteps()
    {
        return new \PHPixie\ORM\Steps();
    }
	
    public function planners()
    {
        if ($this->planners === null) {
            $this->planners = $this->buildPlanners($this->steps());
		}
        return $this->planners;
    }
    
    public function buildPlanners($steps)
    {
        return new \PHPixie\ORM\Planners($steps);
    }
    
    public function plans()
    {
        if ($this->plans === null)
            $this->plans = $this->buildPlans();
        return $this->plans;
    }
    
    public function buildPlans()
    {
        return new \PHPixie\ORM\Plans();
    }
	
	public function databaseConnection($name)
	{
		return $this->database->connection($name);
	}
	
	public function query($modelName)
	{
		$conditions = $this->conditions();
		$mapper = $this->mapper();
		$relationshipMap = $this->relationshipMap();
		
		return new Query($conditions, $mapper, $relationhipMap, $modelName);
	}
}