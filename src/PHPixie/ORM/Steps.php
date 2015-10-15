<?php

namespace PHPixie\ORM;

class Steps
{
    /**
     * @type \PHPixie\ORM\Builder
     */
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
        return new Steps\Result\Filter($result, $fields);
    }

    public function in($placeholder, $placeholderField, $resultStep, $resultField)
    {
        return new Steps\Step\In($placeholder, $placeholderField, $resultStep, $resultField);
    }

    public function pivotCartesian($fields, $resultFiters)
    {
        return new Steps\Step\Pivot\Cartesian($fields, $resultFiters);
    }

    public function batchInsert($insertQuery, $dataStep)
    {
        $queryPlanner = $this->ormBuilder->planners()->query();
        return new Steps\Step\Query\Insert\Batch($queryPlanner, $insertQuery, $dataStep);
    }
    
    public function uniqueDataInsert($dataStep, $selectQuery)
    {
        return new Steps\Step\Query\Insert\Batch\Data\Unique($dataStep, $selectQuery);
    }
    
    public function updateMap($updateQuery, $map, $resultStep)
    {
        return new Steps\Step\Update\Map($updateQuery, $map, $resultStep);
    }
}
