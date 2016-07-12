<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

abstract class Repository implements \PHPixie\ORM\Models\Type\Database\Repository
{
    /**
     * @type \PHPixie\ORM\Models\Type\Database
     */
    protected $databaseModel;

    /**
     * @type \PHPixie\ORM\Database
     */
    protected $database;
    protected $config;
    
    public function __construct($databaseModel, $database, $config)
    {
        $this->databaseModel = $databaseModel;
        $this->database = $database;
        $this->config = $config;
    }
    
    public function config()
    {
        return $this->config;
    }
    
    public function modelName()
    {
        return $this->config->model;
    }

    public function databaseModel()
    {
        return $this->databaseModel;
    }

    public function query()
    {
        return $this->databaseModel->query($this->modelName());
    }
    
    public function create($data = null)
    {
        return $this->entity($data);
    }
    
    public function load($data)
    {
        return $this->entity($data, false);
    }
    
    protected function entity($data = null, $isNew = true)
    {
        $modelName = $this->modelName();
        $data = $this->buildData($data);
        return $this->databaseModel->entity($modelName, $data, $isNew);
    }

    public function connection()
    {
        return $this->database->connection($this->config->connection);
    }
    
    public function delete($entity)
    {
        if ($entity->isDeleted())
            throw new \PHPixie\ORM\Exception\Entity("This model has already been deleted.");

        if (!$entity->isNew()) {
            $this->query()->in($entity)->delete();
        }

        $entity->setIsDeleted(true);
    }

    public function save($entity)
    {
        if ($entity->isDeleted())
            throw new \PHPixie\ORM\Exception\Entity("Deleted models cannot be saved.");
        
        $data = $entity->data();

        if($entity->isNew()){
            
            $this->insertEntityData($data);
            if($entity->id() === null) {
                $id = $this->connection()->insertId();
                $entity->setId($id);
            }
            
            $entity->setIsNew(false);
        } else {
            $this->updateEntityData($entity->id(), $data);
        }

        $data->setCurrentAsOriginal();
    }
    
    protected function insertEntityData($data)
    {
        $this->databaseInsertQuery()
            ->data((array) $data->data())
            ->execute();
    }

    public function databaseSelectQuery()
    {
        return $this->setQuerySource($this->connection()->selectQuery());
    }
    
    public function databaseUpdateQuery()
    {
        return $this->setQuerySource($this->connection()->updateQuery());
    }
    
    public function databaseDeleteQuery()
    {
        return $this->setQuerySource($this->connection()->deleteQuery());
    }
    
    public function databaseInsertQuery()
    {
        return $this->setQuerySource($this->connection()->insertQuery());
    }
    
    public function databaseCountQuery()
    {
        return $this->setQuerySource($this->connection()->countQuery());
    }
    
    abstract protected function setQuerySource($query);
    abstract protected function updateEntityData($id, $data);

}
