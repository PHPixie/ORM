<?php

namespace PHPixie\ORM\Drivers\Driver\SQL;

abstract class Config extends \PHPixie\ORM\Models\Type\Database\Config
{
    public $table;
    
    protected function processConfig($configSlice, $inflector)
    {
        if (($this->table = $configSlice->get('table', null)) === null) {
            $this->table = $inflector->plural($this->model);
        }
        
        parent::processConfig($configSlice, $inflector);
    }
    
    protected function idField($configSlice)
    {
        return $configSlice->get('id', 'id');
    }
}