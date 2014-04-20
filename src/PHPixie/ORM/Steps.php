<?php

namespace \PHPixie\ORM\Planners;

use \PHPixie\ORM\Planners\Steps\Step;

class Steps
{
    protected $planners;
    
    public function __construct($planners)
    {
        $this->planners = $planners;
    }
    
    public function query($query)
    {
        return new Step\Query($query);
    }

    public function result($query)
    {
        return new Step\Query\Result\Single($query);
    }

    public function reusableResult($query)
    {
        return new Step\Query\Result\Reusable($query);
    }

    public function in($query, $placeholder, $logic, $negated, $field)
    {
        return new Step\In($query, $placeholder, $logic, $negated, $field);
    }
    
    public function pivotCartesian($resultSteps)
    {
        return new Step\Pivot\Cartesian($resultSteps);
    }
    
    public function pivotInsert($queryConnection, $queryTarget, $fields, $cartesianStep)
    {
        return new Step\Pivot\Insert($queryPlanner, $queryConnection, $queryTarget, $fields, $cartesianStep);
    }
}
