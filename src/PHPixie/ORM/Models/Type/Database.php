<?php

namespace PHPixie\ORM\Models\Type;

class Database extends \PHPixie\ORM\Models\Model
{
    protected $database;
    protected $drivers;
    protected $conditions;
    protected $mappers;
    protected $values;
    
    protected $wrapper;
    
    protected $wrappedRepositories = array();
    protected $wrappedEntities = array();
    protected $wrappedQueries = array();
    
    public function __construct($models, $relationships, $configs, $database, $drivers, $conditions, $mappers, $values)
    {
        parent::__construct($models, $relationships, $configs);
        
        $this->database = $database;
        $this->drivers = $drivers;
        $this->conditions = $conditions;
        $this->mappers = $mappers;
        $this->values = $values;
        
        if($this->wrapper !== null) {
            $this->wrappedRepositories = $this->wrapper->databaseRepositories();
            $this->wrappedEntities     = $this->wrapper->databaseEntities();
            $this->wrappedQueries      = $this->wrapper->databaseQueries();
        }
    }
    
    public function buildConfig($modelName, $configSlice)
    {
        $driverName = $this->database->connectionDriverName($configSlice->get('connection', 'default'));
        $driver = $this->drivers->get($driverName);
        return $driver->config($this->config->inflector(), $modelName, $configSlice);
    }
    
    public function repository($modelName)
    {
        $config = $this->config($modelName);
        $driver = $this->drivers->get($config->driver);
        $repository = $driver->repository($modelName, $config);
        
        if(array_key_exists($this->wrappedRepositories, $modelName)) {
            $repository = $this->wrapper->databaseRepositoryWrapper($repository);
        }
        
        return $repository;
    }
    
    public function entity($repository, $data, $isNew = true)
    {
        $entity = $driver->entity(
            $this->relationships->map(),
            $repository,
            $data,
            $isNew
        );
        
        if(array_key_exists($this->wrappedEntities, $repository->modelName())) {
            $entity = $this->wrapper->databaseEntityWrapper($entity);
        }
        
        return $entity;
    }
    
    public function query($config)
    {
        $driver = $this->drivers->get($config->driver);
        
        $query = $driver->query(
            $this->values,
            $this->mappers->query(),
            $this->relationships->map(),
            $this->conditions->container(),
            $config
        );
        
        if(array_key_exists($config->model, $this->wrappedQueries)) {
            $query = $this->wrapper->databaseQueryWrapper($query);
        }
        
        return $query;
    }
    
    public function type()
    {
        return 'database';
    }

}