<?php

namespace \PHPixie\ORM\Planners\Planner\Update;

class Modifiers
{
    protected $planners;
    protected $steps;
    
    public function __construct($planners, $step)
    {
        $this->planners = $planners;
        $this->steps = $steps;
    }
    
    public function data($data)
    {
        return Modifiers\Data($data);
    }
    
    public function query($dataField, $query, $queryField)
    {
        return Modifiers\Query($dataField, $query, $queryField);
    }
    
    public function result($resultStep, $fieldMap)
    {
        return Modifiers\Result($resultStep, $fieldMap);
    }
}