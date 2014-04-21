<?php

namespace PHPixie\ORM\Drivers\Driver\PDO\Repository;

class Database extends \PHPixie\ORM\Model\Repository\Database
{
    protected $table;

    public function __construct($ormBuilder, $driver, $dataBuilder, $inflector, $modelName, $config)
    {
        parent::__construct($ormBuilder, $driver, $dataBuilder, $inflector, $modelName, $config);
        if (($this->table = $config->get('table', null)) === null)
            $this->table = $inflector->plural($modelName);
    }

    public function databaseQuery($type = 'select')
    {
        return $this->connection()
                    ->query($type)
                    ->table($this->table);
    }

    public function processSave($model)
    {
        $data = $model->data();
        $data = $data->getDataDiff();
        $idField = $this->idField;

        if ($model->isNew()) {
            $this->dbQuery('insert')
                        ->data($data)
                        ->execute();
            $model->setIsNew(false);
        } else {
            $this->dbQuery('update')
                ->data($data)
                ->where($idField, $model->id())
                ->execute();
            $model->$idField => $this->connection()->insertId();
        }

        $data->setCurrentAsOriginal();
    }

    protected function buildModel($data = null)
    {
        $data = $this->dataBuilder->map($data);

        return $this->driver->model($data, $data !== null);
    }
}
