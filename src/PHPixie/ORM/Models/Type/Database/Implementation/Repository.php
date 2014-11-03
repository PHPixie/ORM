<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

abstract class Repository extends \PHPixie\ORM\Models\Implementation\Repository
                          implements \PHPixie\ORM\Models\Type\Database\Repository
{
    protected $database;
    protected $connectionName;
    protected $idField;

    public function __construct($models, $database, $modelName, $config)
    {
        parent::__construct($models, $modelName);
        $this->database = $database;
        $this->connectionName = $config->get('connection', 'default');
        $this->idField = $config->get('id', $this->defaultIdField());
    }

    public function connectionName()
    {
        return $this->connectionName;
    }

    public function connection()
    {
        return $this->database->connection($this->connectionName);
    }

    public function idField()
    {
        return $this->idField;
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

        $this->processSave($entity);
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
    
    abstract protected function defaultIdField();
    abstract protected function setQuerySource($query);
    abstract protected function processSave($model);

}
