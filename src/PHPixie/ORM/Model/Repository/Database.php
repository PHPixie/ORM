<?php

namespace PHPixie\ORM\Model\Repository

abstract class Database extends \PHPixie\ORM\Model\Repository
{
    protected $connectionName;
    protected $idField;

    public function __construct($ormBuilder, $driver, $dataBuilder, $inflector, $modelName, $config)
    {
        parent::__construct($ormBuilder, $driver, $dataBuilder, $inflector, $modelName, $config);
        $this->connectionName = $modelConfig->get('connection', 'default');
        $this->idField = $modelConfig->get('idField', 'id');
    }

    public function connectionName()
    {
        return $this->connectionName;
    }

    public function connection()
    {
        return $this->ormBuilder->databaseConnection($this->connectionName);
    }

    public function idField()
    {
        return $this->idField;
    }

    public function delete($model)
    {
        if ($model->isDeleted())
            throw new \PHPixie\ORM\Exception\Model("This model has already been deleted.");

        if (!$model->isNew()) {
            $this->query()->in($model)->delete();
        }

        $model->setDeleted(true);
    }

    public function model()
    {
        return $this->buildModel();
    }

    public function load($data)
    {
        return $this->buildModel($data);
    }

    public function save($model)
    {
        if ($model->isDeleted())
            throw new \PHPixie\ORM\Exception\Model("Deleted models cannot be saved.");

        $this->processSave($model);
    }

    public function modelData($model)
    {
        $data = $model->data();
        foreach($model->relationshipProperties() as $name => $property)
            if($property->isLoaded())
                $data->$name = $property->data();

        return $data;
    }

    abstract protected function processSave($model);
    abstract protected function buildModel($data = null);
    abstract public function databaseQuery($type = 'select');
}
