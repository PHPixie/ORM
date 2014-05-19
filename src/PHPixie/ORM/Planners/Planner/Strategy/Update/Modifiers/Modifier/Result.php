<?php

namespace \PHPixie\ORM\Planners\Planner\Update\Modifier;

class Result
{
    protected $resultStep;
    protected $fieldMap;
    
    public function __construct($resultStep, $fieldMap)
    {
        $this->resultStep = $resultStep;
        $this->fieldMap = $fieldMap;
    }
}