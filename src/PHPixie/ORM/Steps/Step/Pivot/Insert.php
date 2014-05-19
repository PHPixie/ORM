<?php

namespace PHPixie\ORM\Steps\Step\Pivot;

class Insert extends \PHPixie\ORM\Steps\Step\Insert\Batch
{
    protected $cartesianStep;
    
    public function __construct($queryPlanner, $insertQuery, $fields, $cartesianStep)
    {
        parent::__construct($queryPlanner, $insertQuery);
        $this->fields = $fields;
        $this->cartesianStep = $cartesianStep;
    }
    
    protected function prepareBatchData()
    {
        $this->data = $this->cartesianStep->product();
    }
}