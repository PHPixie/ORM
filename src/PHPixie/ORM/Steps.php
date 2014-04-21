<?php

namespace \PHPixie\ORM;

class Steps
{
    protected $planners;

    public function __construct($planners)
    {
        $this->planners = $planners;
    }

    public function query($query)
    {
        return new Steps\Step\Query($query);
    }

    public function result($query)
    {
        return new Steps\Step\Query\Result\Single($query);
    }

    public function reusableResult($query)
    {
        return new Steps\Step\Query\Result\Reusable($query);
    }

    public function in($query, $placeholder, $logic, $negated, $field)
    {
        return new Steps\Step\In($query, $placeholder, $logic, $negated, $field);
    }

    public function pivotCartesian($resultSteps)
    {
        return new Steps\Step\Pivot\Cartesian($resultSteps);
    }

    public function pivotInsert($queryConnection, $queryTarget, $fields, $cartesianStep)
    {
        return new Steps\Step\Pivot\Insert($queryPlanner, $queryConnection, $queryTarget, $fields, $cartesianStep);
    }
}
