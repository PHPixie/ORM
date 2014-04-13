<?php

namespace PHPixie\ORM\Driver\Mongo\Embedded;

class Repository extends \PHPixie\ORM\Repository
{
    public function __construct($modelName, $connection, $pluralName, $config)
    {
        parent::__construct($modelName, $connection, $pluralName, $config);
    }

    public function dbQuery($type)
    {
        throw new \PHPixie\ORM\Exception\Mapper("Embedded models cannot be queried");
    }

    public function idField()
    {
        throw new \PHPixie\ORM\Exception\Mapper("Embedded models do not have an id field");
    }
    
    public function save($model)
    {
        throw new \PHPixie\ORM\Exception\Mapper("Embedded models cannot be saved individually. Save the root model instead.");
    }
    
    public function loadModel()
}
