<?php

namespace PHPixie\ORM\Driver\Mongo;

class Repository extends \PHPixie\ORM\Repository
{
    protected $collectionName;
    protected $idField;

    public function __construct($modelName, $connection, $pluralName, $config)
    {
        parent::__construct($modelName, $connection, $pluralName, $config);
        $this->idField  = $config->get('id_field', '_id');
    }

    public function dbQuery($type)
    {
        return $this->connection()
                    ->query($type)
                    ->collection($this->collection);
    }

    public function idField()
    {
        return $this->idField;
    }
}
