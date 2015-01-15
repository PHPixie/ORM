<?php

namespace PHPixie\ORM\Planners\Planner;

class Query
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
        if ($query instanceof \PHPixie\Database\Type\SQL\Query) {
            return $this->strategies->sqlQuery();
        }
        
        if($query instanceof \PHPixie\Database\Driver\Mongo\Query) {
            return $this->strategies->mongoQuery();
        }
        
        $class = get_class($query);
        throw new \PHPixie\ORM\Exception\Planner("No strategies defined for '$class' queries");
    }
}
