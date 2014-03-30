<?php

namespace PHPixie\ORM\Driver\Mongo;

class Repository extends \PHPixie\ORM\Repository
{
    protected $collectionName;
    protected $idField;

    public function __construct($modelName, $connection, $pluralName, $config)
    {
        parent::__construct($modelName, $connection, $pluralName, $config);
        $this->idField  = $config->get('id_field', '_id');
    }

    public function dbQuery($type)
    {
        return $this->connection()
                    ->query($type)
                    ->collection($this->collection);
    }

    public function idField()
    {
        return $this->idField;
    }
    
    public function save($model)
    {
        $data = $model->data();
        list($set, $unset) = $data->getDataDiff();
        $idField = $this->idField;
        
        if ($model->isNew()) {
            $this->dbQuery('insert')
                        ->data($set)
                        ->execute();
            $model->$idField => $this->connection()->insertId();
            $model->setIsNew(false);
        }else {
            $this->dbQuery('update')
                ->data($set)
                ->unset($unset)
                ->where($idField, $model->id())
                ->execute();
        }
        
        $data->setCurrentAsOriginal();
    }
}
