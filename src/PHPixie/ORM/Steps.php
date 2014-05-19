<?php

namespace PHPixie\ORM;

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
        return new Steps\Step\Query\Result\SingleUse($query);
    }

    public function reusableResult($query)
    {
        return new Steps\Step\Query\Result\Reusable($query);
    }

    public function in($placeholder, $placeholderField, $resultStep, $resultField)
    {
        return new Steps\Step\In($placeholder, $placeholderField, $resultStep, $resultField);
    }

    public function pivotCartesian($resultFiters)
    {
        return new Steps\Step\Pivot\Cartesian($resultFiters);
    }

    public function pivotInsert($insertQuery, $fields, $cartesianStep)
    {
        $queryPlanner = $this->planners->query();
        return new Steps\Step\Pivot\Insert($queryPlanner, $insertQuery, $fields, $cartesianStep);
    }
}
