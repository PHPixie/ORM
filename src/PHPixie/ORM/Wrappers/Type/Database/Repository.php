<?php

namespace PHPixie\ORM\Wrappers\Type\Database;

class Repository implements \PHPixie\ORM\Models\Type\Database\Repository
{
    /**
     * @var \PHPixie\ORM\Drivers\Driver\PDO\Repository
     */
    protected $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function databaseModel()
    {
        return $this->repository->databaseModel();
    }

    public function config()
    {
        return $this->repository->config();
    }
    
    public function modelName()
    {
        return $this->repository->modelName();
    }
    
    public function save($entity)
    {
        $this->repository->save($entity);
    }
    
    public function delete($entity)
    {
        $this->repository->delete($entity);
    }
    
    public function load($data)
    {
        return $this->repository->load($data);
    }
    
    public function create($data = null)
    {
        return $this->repository->create($data);
    }
    
    public function query()
    {
        return $this->repository->query();
    }
    
    public function connection()
    {
        return $this->repository->connection();
    }
    
    public function databaseSelectQuery()
    {
        return $this->repository->databaseSelectQuery();
    }
    
    public function databaseUpdateQuery()
    {
        return $this->repository->databaseUpdateQuery();
    }
    
    public function databaseDeleteQuery()
    {
        return $this->repository->databaseDeleteQuery();
    }
        
    public function databaseInsertQuery()
    {
        return $this->repository->databaseInsertQuery();
    }
    
    public function databaseCountQuery()
    {
        return $this->repository->databaseCountQuery();
    }
    
    
}