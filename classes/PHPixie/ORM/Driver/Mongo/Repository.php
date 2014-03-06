<?php

namespace PHPixie\ORM\Driver\Mongo;

class Repository extends \PHPixie\ORM\Repository
{
    protected $collection;
    protected $idField;

    public function __construct($db, $modelName, $pluralName, $config)
    {
        parent::__construct($modelName, $pluralName, $config);
        $this->collection = $config->get('collection', $pluralName);
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
