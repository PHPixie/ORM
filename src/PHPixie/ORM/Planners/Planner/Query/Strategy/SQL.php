<?php

namespace PHPixie\ORM\Planners\Planner\Query\Strategy;

class SQL extends \PHPixie\ORM\Planners\Planner\Query\Strategy
{
    public function setSource($query, $source)
    {
        $query->table($source);
    }

    public function setBatchData($query, $fields, $data)
    {
        $query->batchData($fields, $data);
    }
}
