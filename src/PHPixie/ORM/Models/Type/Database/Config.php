<?php

namespace PHPixie\ORM\Models\Type\Database;

abstract class Config extends \PHPixie\ORM\Models\Model\Config
{
    public $idField;
    public $connection;
    
    protected function type()
    {
        return 'database';
    }
    
    protected function processConfig($config, $inflector)
    {
        $this->connection = $config->get('connection', 'default');
        $this->idField = $config->get('id', $this->defaultIdField());
    }
    
    abstract protected function defaultIdField();
}