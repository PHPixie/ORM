<?php

namespace \PHPixie\ORM\Planners\Planner\Update\Modifier;

class Query
{
    protected $dataField;
    protected $query;
    protected $queryField;
    
    public function __construct($dataField, $query, $queryField)
    {
        $this->dataField = $dataField;
        $this->query = $query;
        $this->queryField = $queryField;
    }
}