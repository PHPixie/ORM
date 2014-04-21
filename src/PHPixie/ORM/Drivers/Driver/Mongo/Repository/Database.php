<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo\Repository;

class Database extends \PHPixie\ORM\Model\Repository\Database
{

    protected $collection;

    public function __construct($ormBuilder, $driver, $dataBuilder, $inflector, $modelName, $config)
    {
        parent::__construct($ormBuilder, $driver, $dataBuilder, $inflector, $modelName, $config);
        if (($this->collection = $config->get('table', null)) === null)
            $this->collection = $inflector->plural($modelName);
    }

    public function databaseQuery($type = 'select')
    {
        return $this->connection()
                    ->query($type)
                    ->collection($this->collection);
    }

    public function processSave($model)
    {
        $data = $model->data();
        $diff = $data->getDataDiff();

        if ($model->isNew()) {
            $this->databaseQuery('insert')
                        ->data($diff->set())
                        ->execute();
            $model->setId($this->connection()->insertId());
            $model->setIsNew(false);
        } else {
            $this->dbQuery('update')
                ->data($diff->set())
                ->unset($diff->unset())
                ->where($idField, $model->id())
                ->execute();
        }

        $data->setCurrentAsOriginal();
    }

    protected function buildModel($data = null)
    {
        $data = $this->dataBuilder->document($data);

        return $this->driver->databaseModel($data, $data !== null);
    }
}
