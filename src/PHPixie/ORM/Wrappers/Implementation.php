<?php

namespace PHPixie\ORM\Wrappers;

class Implementation implements \PHPixie\ORM\Wrappers
{
    protected $databaseRepositories = array();
    protected $databaseQueries      = array();
    protected $databaseEntities     = array();
    protected $embeddedEntities     = array();
    
    public function databaseRepositories()
    {
        return $this->databaseRepositories;
    }
    
    public function databaseQueries()
    {
        return $this->databaseQueries;
    }
    
    public function databaseEntities()
    {
        return $this->databaseEntities;
    }
    
    public function embeddedEntities()
    {
        return $this->embeddedEntities;
    }
    
    public function databaseRepositoryWrapper($repository)
    {
        $method = $repository->modelName().'Repository';
        return $this->$method($repository);
    }
    
    public function databaseQueryWrapper($query)
    {
        $method = $query->modelName().'Query';
        return $this->$method($query);
    }
    
    public function databaseEntityWrapper($entity)
    {
        return $this->entityWrapper($entity);
    }
    
    public function embeddedEntityWrapper($entity)
    {
        return $this->entityWrapper($entity);
    }
    
    protected function entityWrapper($entity)
    {
        $method = $entity->modelName().'Entity';
        return $this->$method($entity);
    }
}