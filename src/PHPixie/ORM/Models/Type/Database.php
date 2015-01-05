<?php

namespace PHPixie\ORM\Models\Type;

class Database extends \PHPixie\ORM\Models\Model
{
    protected $database;
    protected $drivers;
    protected $conditions;
    protected $mappers;
    protected $values;
    
    protected $repositories = array();
    
    public function __construct($models, $relationships, $configs, $database, $drivers, $conditions, $mappers, $values)
    {
        parent::__construct($models, $relationships, $configs);
        
        $this->database = $database;
        $this->drivers = $drivers;
        $this->conditions = $conditions;
        $this->mappers = $mappers;
        $this->values = $values;
        
    }
    
    protected function buildConfig($modelName, $configSlice)
    {
        $driverName = $this->database->connectionDriverName($configSlice->get('connection', 'default'));
        $driver = $this->drivers->get($driverName);
        return $driver->config($this->config->inflector(), $modelName, $configSlice);
    }
    
    protected function buildRepository($modelName)
    {
        $config = $this->config($modelName);
        $driver = $this->drivers->get($config->driver);
        
        $repository = $driver->repository($this->database, $this, $config);
        
        if($this->hasWrapper('databaseRepositories', $config->model)) {
            $repository = $this->wrappers->databaseRepositoryWrapper($repository);
        }
        
        return $repository;
    }
    
    public function repository()
    {
        if(!array_key_exists($amodelName, $this->repositories)) {
            $this->repositories[$modelName] = $this->buildRepository($modelName);
        }
        
        return $this->repositories[$modelName];
    }
    
    public function entity($repository, $data, $isNew)
    {
        $config = $repository->config();
        $driver = $this->drivers->get($config->driver);
        
        $entity = $driver->entity(
            $this->relationships->map(),
            $repository,
            $data,
            $isNew
        );
        
        if($this->hasWrapper('databaseEntities', $config->model)) {
            $entity = $this->wrappers->databaseEntityWrapper($entity);
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
        
        if($this->hasWrapper('databaseQueries', $config->model)) {
            $query = $this->wrappers->databaseQueryWrapper($query);
        }
        
        return $query;
    }
    
    public function type()
    {
        return 'database';
    }

}