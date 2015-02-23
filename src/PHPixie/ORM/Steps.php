<?php

namespace PHPixie\ORM;

class Steps
{
    protected $ormBuilder;

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    public function query($query)
    {
        return new Steps\Step\Query($query);
    }
    
    public function count($query)
    {
        return new Steps\Step\Query\Count($query);
    }

    public function iteratorResult($query)
    {
        return new Steps\Step\Query\Result\Iterator($query);
    }

    public function reusableResult($query)
    {
        return new Steps\Step\Query\Result\Reusable($query);
    }
    
    public function resultFilter($result, $fields)
    {
        return new Steps\ResultFilter($result, $fields);
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
        $queryPlanner = $this->ormBuilder->planners()->query();
        return new Steps\Step\Pivot\Insert($queryPlanner, $insertQuery, $fields, $cartesianStep);
    }
    
    public function updateMap($updateQuery, $map, $resultStep)
    {
        return new Steps\Step\Update\Map($updateQuery, $map, $resultStep);
    }
}
