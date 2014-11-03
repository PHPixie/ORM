<?php

namespace PHPixie\ORM\Drivers\Driver\SQL;

abstract class Repository extends \PHPixie\ORM\Models\Type\Database\Implementation\Repository
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

    protected function updateEntityData($id, $data)
    {
        $set = (array) $data->diff()->set();
        $this->databaseUpdateQuery()
            ->set($set)
            ->where($this->idField, $id)
            ->execute();
    }
    
    public function tableName()
    {
        return $this->tableName;
    }

    protected function buildData($data = null)
    {
        return $this->dataBuilder->map($data);
    }
    
    protected function setQuerySource($query)
    {
        $query->table($this->tableName);
        return $query;
    }
    
    protected function defaultIdField()
    {
        return 'id';
    }
}
