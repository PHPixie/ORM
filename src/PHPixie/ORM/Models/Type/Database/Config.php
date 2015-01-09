<?php

namespace PHPixie\ORM\Models\Type\Database;

abstract class Config extends \PHPixie\ORM\Models\Model\Config
{
    public $idField;
    public $connection;
    public $driver;

    public function __construct($inflector, $modelName, $configSlice, $driverName)
    {
        $this->driver = $driverName;
        parent::__construct($inflector, $modelName, $configSlice);
    }
    
    protected function type()
    {
        return 'database';
    }
    
    protected function processConfig($configSlice, $inflector)
    {
        $this->connection = $configSlice->get('connection', 'default');
        $this->idField = $configSlice->get('id', $this->defaultIdField());
    }
    
    abstract protected function defaultIdField();
}