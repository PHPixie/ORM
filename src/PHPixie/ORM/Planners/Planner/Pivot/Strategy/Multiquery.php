<?php

namespace PHPixie\ORM\Planners\Planner\Pivot\Strategy;

class Multiquery extends \PHPixie\ORM\Planners\Planner\Pivot\Strategy
{
    public function link($pivot, $firstSide, $secondSide, $plan)
    {
        $resultFilters = array();
        foreach (array($firstSide, $secondSide) as $side) {
            $idQuery = $this->idQuery($side, $plan);
            $resultStep = $this->steps->iteratorResult($idQuery);
            $idField = $side->repository()->config()->idField;
            $resultFilter = $this->steps->resultFilter($resultStep, array($idField));
            $plan->add($resultStep);
            $resultFilters[] = $resultFilter;
        }

        $cartesianStep = $this->steps->pivotCartesian(
            array(
                $firstSide->pivotKey(),
                $secondSide->pivotKey()
            ),
            $resultFilters
        );
        
        $plan->add($cartesianStep);
        
        $selectQuery = $pivot->databaseSelectQuery();
        $uniqueDataStep  = $this->steps->uniqueDataInsert($cartesianStep, $selectQuery);
        $plan->add($uniqueDataStep);
        
        $insertQuery = $pivot->databaseInsertQuery();
        $insertStep = $this->steps->batchInsert($insertQuery, $uniqueDataStep);
        $plan->add($insertStep);
    }
}
