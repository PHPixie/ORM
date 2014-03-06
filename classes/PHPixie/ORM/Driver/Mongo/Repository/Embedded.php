<?php

namespace PHPixie\ORM\Driver\Mongo\Repository;

class Embedded extends \PHPixie\ORM\Repository
{
    protected $collection;
    protected $idField = '_id';
    protected $path;

    public function __construct($db, $modelName, $pluralName, $config)
    {
        parent::__construct($modelName, $pluralName, $config);
        $this->collection = $config->get('collection', $pluralName)
    }

    public function dbQuery($type)
    {
        return $this->orm->embeddedQuery($type, $connectionName);
    }

    public function idField()
    {
        return $this->idField;
    }
}
