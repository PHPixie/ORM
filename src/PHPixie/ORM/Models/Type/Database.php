<?php

namespace PHPixie\ORM\Models\Type;

class Database extends \PHPixie\ORM\Models\Model
{
    /**
     * @type \PHPixie\ORM\Database
     */
    protected $database;

    /**
     * @type \PHPixie\ORM\Drivers
     */
    protected $drivers;

    /**
     * @type \PHPixie\ORM\Conditions
     */
    protected $conditions;

    /**
     * @type \PHPixie\ORM\Mappers
     */
    protected $mappers;

    /**
     * @type \PHPixie\ORM\Values
     */
    protected $values;
    
    protected $repositories = array();
    
    public function __construct($models, $configs, $database, $drivers)
    {
        parent::__construct($models, $configs);
        
        $this->database = $database;
        $this->drivers = $drivers;
    }
    
    protected function buildConfig($modelName, $configSlice)
    {
        $connectionName = $configSlice->get('connection', 'default');
        $driverName = $this->database->connectionDriverName($connectionName);
        $driver = $this->drivers->get($driverName);
        return $driver->config($modelName, $configSlice);
    }
    
    protected function buildRepository($modelName)
    {
        $config = $this->config($modelName);
        $driver = $this->drivers->get($config->driver);
        
        $repository = $driver->repository($config);
        
        if($this->hasWrapper('databaseRepositories', $config->model)) {
            $repository = $this->wrappers->databaseRepositoryWrapper($repository);
        }
        
        return $repository;
    }

    /**
     * @param string $modelName
     * @return Database\Repository
     */
    public function repository($modelName)
    {
        if(!array_key_exists($modelName, $this->repositories)) {
            $this->repositories[$modelName] = $this->buildRepository($modelName);
        }
        
        return $this->repositories[$modelName];
    }

    /**
     * @param $modelName
     * @param $data
     * @param $isNew
     * @return Database\Entity
     */
    public function entity($modelName, $data, $isNew)
    {
        $repository = $this->repository($modelName);
        $config = $repository->config();
        $driver = $this->drivers->get($config->driver);
        
        $entity = $driver->entity(
            $repository,
            $data,
            $isNew
        );
        
        if($this->hasWrapper('databaseEntities', $config->model)) {
            $entity = $this->wrappers->databaseEntityWrapper($entity);
        }
        
        return $entity;
    }

    /**
     * @param $modelName
     * @return Database\Query
     * @throws \PHPixie\ORM\Exception\Model
     */
    public function query($modelName)
    {
        $config = $this->config($modelName);
        $driver = $this->drivers->get($config->driver);
        $query = $driver->query($config);
        
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