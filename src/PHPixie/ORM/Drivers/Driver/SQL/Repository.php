<?php

namespace PHPixie\ORM\Repositories\Driver\SQL;

class Repository extends \PHPixie\ORM\Repositories\Type\Database
{
    protected $table;

    public function __construct($dataBuilder, $inflector, $modelName, $config)
    {
        parent::__construct($dataBuilder, $inflector, $modelName, $config);
        if (($this->table = $config->get('table', null)) === null)
            $this->table = $inflector->plural($modelName);
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
