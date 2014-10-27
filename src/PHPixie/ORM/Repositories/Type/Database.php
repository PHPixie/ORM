<?php

namespace PHPixie\ORM\Repositories\Type;

abstract class Database extends \PHPixie\ORM\Repositories\Repository
{
    protected $database;
    protected $connectionName;
    protected $idField;
    protected $defaultIdField = 'id';

    public function __construct($database, $dataBuilder, $modelName, $config)
    {
        $this->database = $database;
        parent::__construct($dataBuilder, $modelName);
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
    
    public function query()
    {
        $this->ormBuilder->query($this->modelName());
    }

    public function delete($model)
    {
        if ($model->isDeleted())
            throw new \PHPixie\ORM\Exception\Model("This model has already been deleted.");

        if (!$model->isNew()) {
            $this->query()->in($model)->delete();
        }

        $model->setDeleted(true);
    }

    public function model()
    {
        return $this->buildModel();
    }

    public function load($data)
    {
        $
        return $this->buildModel($data);
    }

    public function save($model)
    {
        if ($model->isDeleted())
            throw new \PHPixie\ORM\Exception\Model("Deleted models cannot be saved.");

        $this->processSave($model);
    }

    public function modelData($model)
    {
        $data = $model->data();
        foreach($model->relationshipProperties() as $name => $property)
            if($property->isLoaded())
                $data->$name = $property->data();

        return $data;
    }

    abstract protected function processSave($model);
    abstract protected function buildModel($data = null);
    
    public function databaseSelectQuery()
    {
        return $this->connection()->select();
    }
    
    public function databaseUpdateQuery()
    {
        return $this->connection()->update();
    }
    
    public function databaseDeleteQuery()
    {
        return $this->connection()->delete();
    }
    
    public function databaseInsertQuery()
    {
        return $this->connection()->insert();
    }
    
    public function databaseCountQuery()
    {
        return $this->connection()->count();
    }
}
