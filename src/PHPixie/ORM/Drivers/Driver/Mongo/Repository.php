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
            $id = '_id';
            
            if(property_exists($data, $id) && $data->$id instanceof \MongoId) {
                $data->$id = (string) $data->$id;
            }

            $data = (array) $data;
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
        print_r($data);
        
        $this->databaseInsertQuery()
            ->data((array) $data)
            ->execute();
    }
    
    protected function getMongoIdParentAndKey($data, $createMissing = false) {
        $idField = $this->config->idField();
        $path = explode('.', $idField);
        
        $key = array_pop($path);
        
        foreach($path as $step) {
            if(!property_exists($data, $step)) {
                if(!$createMissing) {
                    return array(null, $key);
                }
                
                $data->$step = new \stdClass;
            }
            
            $data = $data->$step;
        }
        
        return array($data, $key);
    }

    
}