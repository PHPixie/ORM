<?php

namespace PHPixie\ORM\Driver\PDO;

class Repository extends \PHPixie\ORM\Repository
{
    protected $table;
    protected $idField;

    public function __construct($db, $modelName, $pluralName, $config)
    {
        parent::__construct($modelName, $pluralName, $config);
        $this->table = $config->get('table', $pluralName)
        $this->idField  = $config->get('id_field', 'id');
    }

    public function dbQuery($type)
    {
        return $this->connection()
                    ->query($type)
                    ->table($this->table);
    }

    public function idField()
    {
        return $this->idField;
    }
    
    public function save($model)
    {
        $data = $model->data();
        $data = $data->getDataDiff();
        $idField = $this->idField;
        
        if ($model->isNew()) {
            $this->dbQuery('insert')
                        ->data($data)
                        ->execute();
            $model->setIsNew(false);
        }else {
            $this->dbQuery('update')
                ->data($data)
                ->where($idField, $model->id())
                ->execute();
            $model->$idField => $this->connection()->insertId();
        }
        
        $data->setCurrentAsOriginal();
    }
}
