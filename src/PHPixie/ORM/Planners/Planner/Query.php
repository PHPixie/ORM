<?php

namespace \PHPixie\ORM\Planners\Planner;

class Query extends \PHPixie\ORM\Planners\Planner\Strategy
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
        if ($queryConnection instanceof PHPixie\DB\Driver\PDO\Query)
            return $this->strategy('pdo');
        return $this->strategy('mongo');
    }
    
    protected function buildStrategy($name)
    {
        $class = '\PHPixie\ORM\Planners\Planner\Query\Strategy\\'.$name;
        return new $class;
    }
}