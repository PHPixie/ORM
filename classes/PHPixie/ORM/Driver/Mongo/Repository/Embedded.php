<?php

namespace PHPixie\ORM\Driver\Mongo\Repository;

class Embedded extends \PHPixie\ORM\Driver\Mongo\Repository
{
    protected $path;

    public function __construct($modelName, $connection, $pluralName, $config)
    {
        parent::__construct($modelName, $connection, $pluralName, $config);
        $this->collection = $config->get('collection');
    }

    public function dbQuery($type)
    {
        return $this->orm->embeddedQuery($type, $this->connection);
    }

    public function idField()
    {
        return $this->path.'.'.$this->idField;
    }
    
    public function delete()
    {
        
    }
}
