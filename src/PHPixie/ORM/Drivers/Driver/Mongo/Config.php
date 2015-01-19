<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo;

class Config extends \PHPixie\ORM\Models\Type\Database\Config
{
    public $collection;
    
    protected function processConfig($configSlice, $inflector)
    {
        if (($this->collection = $configSlice->get('collection', null)) === null)
            $this->collection = $inflector->plural($this->model);
    }
    
    protected function driver()
    {
        return 'mongo';
    }
    
    protected function defaultIdField()
    {
        return '_id';
    }
}