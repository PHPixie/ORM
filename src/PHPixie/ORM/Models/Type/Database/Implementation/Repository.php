<?php

namespace PHPixie\ORM\Models\Type\Database\Implementation;

abstract class Repository extends \PHPixie\ORM\Models\Implementation\Repository
                          implements \PHPixie\ORM\Models\Type\Database\Repository
{
    protected $database;
    protected $connectionName;
    protected $idField;
    protected $defaultIdField = 'id';

    public function __construct($models, $database, $modelName, $config)
    {
        parent::__construct($models, $modelName);
        $this->database = $database;
        $this->connectionName = $modelConfig->get('connection', 'default');
        $this->idField = $modelConfig->get('idField', $this->defaultIdField);
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
            $this->query()->in($model)->delete();
        }

        $entity->setDeleted(true);
    }

    public function save($model)
    {
        if ($model->isDeleted())
            throw new \PHPixie\ORM\Exception\Model("Deleted models cannot be saved.");

        $this->processSave($model);
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
        return $this->setQuerySource($this->connection()->countQuery()));
    }
    
    abstract protected function setQuerySource($query);
    abstract protected function processSave($model);
    abstract protected function buildModel($data = null);

}
