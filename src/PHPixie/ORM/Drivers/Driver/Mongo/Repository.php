<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo;

class Repository extends \PHPixie\ORM\Models\Type\Database\Implementation\Repository
{
    protected $collectionName;
    protected $dataBuilder;

    public function __construct($model, $database, $dataBuilder, $config)
    {
        parent::__construct($models, $database, $config);
        $this->dataBuilder = $dataBuilder;
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

    protected function buildData($data = null)
    {
        return $this->dataBuilder->diffableDocument($data);
    }
    
    protected function setQuerySource($query)
    {
        $query->collection($this->config->collection);
        return $query;
    }
}