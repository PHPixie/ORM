<?php

namespace PHPixie\ORM\Drivers\Driver\SQL;

class Repository extends \PHPixie\ORM\Models\Type\Database\Implementation\Repository
{
    protected $tableName;
    protected $dataBuilder;

    public function __construct($models, $database, $dataBuilder, $inflector, $modelName, $config)
    {
        parent::__construct($models, $database, $modelName, $config);
        $this->dataBuilder = $dataBuilder;
        
        if (($this->tableName = $config->get('table', null)) === null)
            $this->tableName = $inflector->plural($modelName);
    }

    public function processSave($model)
    {
        $data = $model->data();
        $idField = $this->idField;

        if ($model->isNew()) {
            $this->databaseInsertQuery()
                ->data($data)
                ->execute();
            
            $model->setField($idField, $this->connection()->insertId());
            $model->setIsNew(false);
        } else {
            $set = $data->diff()->set();
            $this->databaseUpdateQuery()
                ->set($set)
                ->where($idField, $model->id())
                ->execute();
        }

        $data->setCurrentAsOriginal();
    }
    
    public function tableName()
    {
        return $this->tableName;
    }

    protected function buildData($data = null)
    {
        return $this->dataBuilder->map($data);
    }
    
    protected function prepareDatabaseQuery($query)
    {
        $query->table($this->tableName);
        return $query;
    }
}
