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
        $this->selectStrategy($query)->setBatchData($query, $fields, $data);
        return $query;
    }

    protected function selectStrategy($query)
    {
        if ($query instanceof \PHPixie\Database\Type\SQL\Query) {
            return $this->strategy('sql');
        }
        
        if($query instanceof \PHPixie\Database\Driver\Mongo\Query) {
            return $this->strategy('mongo');
        }
        
        $class = get_class($query);
        throw new \PHPixie\ORM\Exception\Planner("No strategies defined for '$class' queries");
    }
    
    protected function buildSqlStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Query\Strategy\SQL();
    }
    
    protected function buildMongoStrategy()
    {
        return new \PHPixie\ORM\Planners\Planner\Query\Strategy\Mongo();
    }
    
}
