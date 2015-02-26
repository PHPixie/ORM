<?php

namespace PHPixie\ORM\Drivers\Driver\Mongo;

class Config extends \PHPixie\ORM\Models\Type\Database\Config
{
    public $collection;
    
    protected function processConfig($configSlice, $inflector)
    {
        if (($this->collection = $configSlice->get('collection', null)) === null) {
            $this->collection = $inflector->plural($this->model);
        }
        
        if($configSlice->get('id', '_id') !== '_id') {
            throw new \Exception();
        }
        parent::processConfig($configSlice, $inflector);
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