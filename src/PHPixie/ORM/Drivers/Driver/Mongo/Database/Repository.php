<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo\Database;

class Repository extends \PHPixie\ORM\Models\Type\Database\Implementation\Repository
{
    protected $collectionName;
    protected $dataBuilder;

    public function __construct($models, $database, $dataBuilder, $inflector, $modelName, $config)
    {
        parent::__construct($models, $database, $modelName, $config);
        $this->dataBuilder = $dataBuilder;
        
        if (($this->collectionName = $config->get('collection', null)) === null)
            $this->collectionName = $inflector->plural($modelName);
    }

    protected function updateEntityData($id, $data)
    {
        $diff = $data->diff();
        $this->databaseUpdateQuery()
            ->set((array) $diff->set())
            ->_unset((array) $diff->remove())
            ->where($this->idField, $id)
            ->execute();
    }
    
    public function collectionName()
    {
        return $this->collectionName;
    }

    protected function buildData($data = null)
    {
        return $this->dataBuilder->diffableDocument($data);
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