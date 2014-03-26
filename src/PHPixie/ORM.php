<?php

namespace PHPixie;

class ORM
{
    protected $db;
    protected $config;
    protected $relationshipMap;
    protected $propertyBuilder;
    protected $repositoryRegistry;
    protected $relationshipTypes = array();
    protected $drivers = array();
    protected $mapper;
    protected $groupMapper;

    public function __construct($db, $config)
    {
        $this->db = $db;
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
        $class = '\PHPixie\ORM\Relationship\Types\\'.$name;

        return new $class($this);
    }

    public function repository($modelName, $modelConfig)
    {
        $connectionName = $modelConfig->get('connection');
        $driverName = $this->db->driverName($connectionName);
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

        return new \PHPixie\ORM\Relationship\Map($this, $relationshipConfig);
    }

    public function repositoryRegistry()
    {
        if($this->repositoryRegistry === null)
            $this->repositoryRegistry = $this->buildRepositoryRegistry();

        return $this->repositoryRegistry;
    }

    protected function buildRepositoryRegistry()
    {
        $modelConfig = $this->config->slice('models');

        return new \PHPixie\ORM\Relationship\Registry($this, $modelConfig);
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

    public function modelProperty($side, $model)
    {
        $relationship = $this->relationshipType($side->relationshipType());

        return $relationship->modelProperty($side);
    }

    public function queryProperty($side, $model)
    {
        $relationship = $this->relationship($side->relationshipType());

        return $relationship->queryProperty($side);
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
        return \PHPixie\ORM\Mapper($this, $this->groupMapper());
    }

    public function conditions()
    {
        if ($this->conditions === null)
            $this->conditions = $this->buildConditions();

        return $this->conditions;
    }

    protected function getConditions()
    {
        return new \PHPixie\DB\Conditions();
    }
}
