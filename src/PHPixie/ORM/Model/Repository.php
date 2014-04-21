<?php

namespace PHPixie\ORM\Model;

abstract class Repository
{
    protected $ormBuilder;
    protected $driver;
    protected $dataBuilder;
    protected $modelName;
    protected $connectionName;

    public function __construct($ormBuilder, $driver, $dataBuilder, $inflector, $modelName, $config)
    {
        $this->ormBuilder = $ormBuilder;
        $this->driver = $driver;
        $this->dataBuilder = $dataBuilder;
        $this->modelName = $modelName;
    }

    public function modelName()
    {
        return $this->modelName;
    }

    public function query()
    {
        $this->ormBuilder->query($this->modelName());
    }

    public function modelDataAsObject($model)
    {
        return $model->data()->currentData();
    }

    abstract public function save($model);
    abstract public function delete($model);
    abstract public function load($data);
    abstract public function model();
}
