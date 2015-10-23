<?php

namespace PHPixie\ORM\Drivers\Driver\SQL;

abstract class Repository extends \PHPixie\ORM\Models\Type\Database\Implementation\Repository
{
    protected $dataBuilder;

    public function __construct($databaseModel, $database, $dataBuilder, $config)
    {
        parent::__construct($databaseModel, $database, $config);
        $this->dataBuilder = $dataBuilder;
    }

    protected function updateEntityData($id, $data)
    {
        $set = (array) $data->diff()->set();
        if(!empty($set)) {
            $this->databaseUpdateQuery()
                ->set($set)
                ->where($this->config->idField, $id)
                ->execute();
        }
    }

    protected function buildData($data = null)
    {
        return $this->dataBuilder->map($data);
    }

    protected function setQuerySource($query)
    {
        $query->table($this->config->table);
        return $query;
    }
}
