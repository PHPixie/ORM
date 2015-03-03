<?php

namespace PHPixie\ORM\Steps\Result;

class Filter
{
    protected $result;
    protected $fields;
    
    public function __construct($result, $fields)
    {
        $this->result = $result;
        $this->fields = $fields;
    }
    
    public function getFirstFieldValues()
    {
        return $this->result->getField($this->fields[0]);
    }
    
    public function getFilteredData()
    {
        return $this->result->getFields($this->fields);
    }
}