<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo\Database;

class Repository extends \PHPixie\ORM\Models\Type\Database\Implementation\Repository
{
    protected $tableName;
    protected $dataBuilder;

    public function __construct($models, $database, $dataBuilder, $inflector, $modelName, $config)
    {
        parent::__construct($models, $database, $modelName, $config);
        $this->dataBuilder = $dataBuilder;
        
        if (($this->collectionName = $config->get('collection', null)) === null)
            $this->collectionName = $inflector->plural($modelName);
    }

    protected function processSave($model)
    {
        $data = $model->data();
        $idField = $this->idField;

        if ($model->isNew()) {
            $object = $data->data();
            $this->databaseInsertQuery()
                ->data($object)
                ->execute();
            
            $id = $this->connection()->insertId();
            $model->setField($idField, $id);
            $model->setId($id);
            $model->setIsNew(false);
        } else {
            $diff = $data->diff();
            $this->databaseUpdateQuery()
                ->set($diff->set())
                ->_unset($diff->unset())
                ->where($idField, $model->id())
                ->execute();
        }

        $data->setCurrentAsOriginal();
    }
    
    public function collectionName()
    {
        return $this->collectionName;
    }

    protected function buildData($data = null)
    {
        return $this->dataBuilder->document($data);
    }
    
    protected function setQuerySource($query)
    {
        $query->collection($this->collectionName);
        return $query;
    }
    
    protected function defaultIdField()
    {
        return '_id';
    }
}