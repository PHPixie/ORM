<?php

namespace PHPixie\ORM;

class  Repository
{
    protected $modelName;
    protected $pluralName;
    protected $connectionName;

    public function __construct($db, $driver, $modelName, $pluralName, $config)
    {
        $this->db = $db;
        $this->driver = $driver;
        $this->modelName = $modelName;
        $this->pluralName = $pluralName;
        $this->connectionName = $config->get('connection', 'default');
    }

    public function connection()
    {
        return $this->db->get($this->connectionName);
    }

    public function load($data, $preloaders = array())
    {
        $model = $this->driver->model($modelName, $data);
        foreach($preloaders as $property => $preloader)
            $model->$property->setValue($preloader->loadFor($model));

        return $model;
    }
}
