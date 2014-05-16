<?php

namespace PHPixie\ORM\Steps;

class ResultFilter
{
    protected $resultStep;
    protected $fields;
    
    public function __construct($resultStep, $fields)
    {
        $this->resultStep = $resultStep;
        $this->fields = $fields;
    }
    
    public function getFirstFieldValues()
    {
        return $this->resultStep->getField($fields[0]);
    }
    
    public function getFilteredData()
    {
        return $this->resultStep->getFields($this->fields);
    }
}