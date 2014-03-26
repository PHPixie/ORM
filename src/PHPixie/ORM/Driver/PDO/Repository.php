<?php

namespace PHPixie\ORM\Driver\PDO;

class Repository extends \PHPixie\ORM\Repository
{
    protected $table;
    protected $idField;

    public function __construct($db, $modelName, $pluralName, $config)
    {
        parent::__construct($modelName, $pluralName, $config);
        $this->table = $config->get('table', $pluralName)
        $this->idField  = $config->get('id_field', 'id');
    }

    public function dbQuery($type)
    {
        return $this->connection()
                    ->query($type)
                    ->table($this->table);
    }

    public function idField()
    {
        return $this->idField;
    }
}
