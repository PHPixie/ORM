<?php

namespace PHPixie\ORM\Relationship\Types\Embedded;

class Repository extends \PHPixie\ORM\Repository
{

    protected $embedded;
    
    public function __construct($orm, $embedded, $modelName, $pluralName, $config)
    {
        parent::__construct($orm, $modelName, null, $pluralName, $config);
        $his->embedded = $embedded;
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
    
    public function loadModel($document)
    {
        return $this->embedded->model();
    }
}
