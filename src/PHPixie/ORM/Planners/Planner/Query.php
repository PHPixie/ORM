<?php

namespace PHPixie\ORM\Planners\Planner;

class Query extends \PHPixie\ORM\Planners\Planner
{
    protected $strategies;
    
    public function __construct($strategies)
    {
        $this->strategies = $strategies;
    }
    
    public function setSource($query, $source)
    {
        $this->selectStrategy($query)->setSource($query, $source);
        return $query;
    }

    public function setBatchData($query, $fields, $data)
    {
        $this->selectStrategy($query)->setBatchData($query, $fields, $data);
        return $query;
    }

    protected function selectStrategy($query)
    {
        if ($query instanceof \PHPixie\Database\SQL\Query){
            return $this->strategies->query('SQL');
        }
        return $this->strategies->query('Mongo');
    }
}
