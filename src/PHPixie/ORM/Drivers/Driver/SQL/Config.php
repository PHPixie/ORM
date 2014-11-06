<?php

namespace PHPixie\ORM\Drivers\Driver\SQL;

abstract class Config extends \PHPixie\ORM\Models\Type\Database\Config
{
    public $table;
    
    protected function processConfig($config, $inflector)
    {
        if (($this->table = $config->get('table', null)) === null)
            $this->table = $inflector->plural($this->modelName);
    }
    
    protected function defaultIdField()
    {
        return 'id';
    }
}