<?php

namespace PHPixie\ORM\Models\Model;

class Database extends \PHPixie\ORM\Models\Model
{
    protected $drivers;
    protected $wrappedEntities;
    
    public function __construct($models, $wrappers, $drivers)
    {
        parent::__construct($models, $wrappers);
        $this->drivers = $drivers;
        $this->wrappedEntities = $wrappers->databaseEntities();
        $this->wrappedRepositories = $wrappers->databaseRepositories();
        $this->wrappedQueries = $wrappers->databaseQueries();
    }
    
    public function buildConfig($modelName, $configSlice)
    {
        $driverName = $this->database->getDriverName($configSlice->get('connection', 'default'));
        $driver = $this->drivers->get($driverName);
        $driver->config($modelName, $configSlice);
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
    
    public function entity($modelName, $repository, $data, $isNew = true)
    {
        $entity = $driver->entity($this->relationshipMap, $repository, $data, $isNew);
        
        if(array_key_exists($this->wrappedEntities, $modelName)) {
            $entity = $this->wrapper->databaseEntityWrapper($entity);
        }
        
        return $entity;
    }
    
    public function query($modelName, $repository, $data, $isNew = true)
    {
        $query = $driver->query($this->relationshipMap, $repository, $data, $isNew);
        
        if(array_key_exists($this->wrappedQueries, $modelName)) {
            $query = $this->wrapper->databaseQueryWrapper($query);
        }
        
        return $query;
    }

}