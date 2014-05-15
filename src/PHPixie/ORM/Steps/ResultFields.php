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
    
    public function field($field = null)
    {
        if($field === null)
            $field = $fields[0];
        
        return $this->resultStep->getField($field);
    }
    
    public function fields()
    {
        $items = array();
        $this->resultStep->result();
        foreach($result as $item)
        {
            $fields = array();
        }
    }
}