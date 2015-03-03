<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo;

class Repository extends \PHPixie\ORM\Models\Type\Database\Implementation\Repository
{
    protected $collectionName;
    protected $dataBuilder;

    public function __construct($models, $database, $dataBuilder, $config)
    {
        parent::__construct($models, $database, $config);
        $this->dataBuilder = $dataBuilder;
    }

    protected function updateEntityData($id, $data)
    {
        $diff = $data->diff();
        $set = (array) $diff->set();
        $remove = (array) $diff->remove();
        if(!empty($set) || !empty($remove)) {
            $this->databaseUpdateQuery()
                ->set($set)
                ->_unset($remove)
                ->where($this->config->idField, $id)
                ->execute();
        }
    }

    protected function buildData($data = null)
    {
        if($data !== null) {
            $data = (object) $data;
            
            if(property_exists($data, '_id')) {
                $data->_id = (string) $data->_id;
            }
        }
        
        return $this->dataBuilder->diffableDocumentFromData($data);
    }
    
    protected function setQuerySource($query)
    {
        $query->collection($this->config->collection);
        return $query;
    }
    
    protected function insertEntityData($data)
    {
        $data = $data->data();
        
        $this->databaseInsertQuery()
            ->data((array) $data)
            ->execute();
    }
}