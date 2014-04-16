<?php

namespace PHPixie\ORM\Model\Repository

abstract class Database extends \PHPixie\ORM\Repositories\Repository
{
    protected $connectionName;
    protected $idField;
    
    public function __construct($orm, $driver, $modelName, $modelConfig)
    {
        parent::__construct($orm, $driver, $modelName, $modelConfig);
        $this->connectionName = $modelConfig->get('connection', 'default');
        $this->idField = $modelConfig->get('idField', 'id');
    }
    
    public function connectionName()
    {
        return $this->connectionName;
    }
    
    public function connection()
    {
        return $this->orm->databaseConnection($this->connectionName);
    }
    
    public function idField()
    {
        return $this->idField;
    }
    
    public function databaseQuery($type = 'select')
    {
        return $this->connection()->query($type);
    }
}