<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo;

class Config extends \PHPixie\ORM\Models\Type\Database\Config
{
    public $collection;
    
    protected function processConfig($config, $inflector)
    {
        if (($this->collection = $config->get('collection', null)) === null)
            $this->collection = $inflector->plural($this->modelName);
    }
    
    protected function defaultIdField()
    {
        return '_id';
    }
}