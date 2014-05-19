<?php

namespace PHPixie\ORM\Planners\Planner;

class Query extends \PHPixie\ORM\Planners\Planner
{
    public function setSource($query, $source)
    {
        $this->selectStrategy($query)->setSource($query, $source);
        return $query;
    }

    public function setBatchData($query, $fields, $data)
    {
        $this->selectStrategy($query)->setBatchData($query, $source);
        return $query;
    }

    protected function selectStrategy($query)
    {
        if ($query instanceof \PHPixie\Database\Driver\PDO\Query){
            return $this->strategies->query('PDO');
        }
        return $this->strategies->query('Mongo');
    }
}
