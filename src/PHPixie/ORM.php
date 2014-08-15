<?php

namespace PHPixie;

class ORM
{
    protected $database;
    protected $config;
    protected $relationshipMap;
    protected $propertyBuilder;
    protected $repositories;
    protected $loaders;
    protected $relationshipTypes = array();
    protected $drivers = array();
    protected $mapper;
    protected $groupMapper;

    public function __construct($database, $config)
    {
        $this->database = $database;
        $this->config = $config;
    }

    public function driver($name)
    {
        if (!isset($this->drivers[$name]))
            $this->drivers[$name] = $this->buildDriver($name);

        return $this->drivers[$name];
    }

    public function buildDriver($name)
    {
        $class = '\PHPixie\ORM\Driver\\'.$name;

        return new $class($this);
    }

    public function relationshipType($name)
    {
        if (!isset($this->relationshipTypes[$name]))
            $this->relationshipTypes[$name] = $this->buildRelationshipType($name);

        return $this->relationshipTypes[$name];
    }

    public function buildRelationshipType($name)
    {
        $class = '\PHPixie\ORM\Relationships\Type\\'.$name;

        return new $class($this);
    }

    public function buildRepository($modelName, $modelConfig)
    {
        $connectionName = $modelConfig->get('connection');
        $driverName = $this->db->connectionDriverName($connectionName);
        $driver = $this->driver($driverName);

        return $driver->repository($modelName, $modelConfig);
    }

    public function relationshipMap()
    {
        if($this->relationshipMap === null)
            $this->relationshipMap = $this->buildRelationshipMap();

        return $this->relationshipMap;
    }

    protected function buildRelationshipMap()
    {
        $relationshipConfig = $this->config->slice('relationships');

        return new \PHPixie\ORM\Relationships\Relationship\Map($this, $relationshipConfig);
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

    public function propertyBuilder()
    {
        if ($this->propertyBuilder === null)
            $this->propertyBuilder = $this->buildPropertyBuilder();

        return $this->propertyBuilder;
    }

    public function buildPropertyBuilder()
    {
        return new \PHPixie\ORM\Properties\Builder($this, $this->relationshipMap());
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

    public function conditions()
    {
        if ($this->conditions === null)
            $this->conditions = $this->buildConditions();

        return $this->conditions;
    }

    protected function buildConditions()
    {
        return new \PHPixie\DB\Conditions();
    }

    public function plan()
    {
        return new \PHPixie\ORM\Plan();
    }

    public function loaderPlan()
    {
        return new \PHPixie\ORM\Plan\Loader();
    }

    public function databaseConnection($name)
    {
        return $this->database->get($name);
    }
}
