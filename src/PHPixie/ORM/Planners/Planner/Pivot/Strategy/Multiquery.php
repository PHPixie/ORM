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
            $resultFilter = $this->steps->resultFilter($resultStep, array($side->repository()->config()->idField));
            $plan->add($resultStep);
            $resultFilters[] = $resultFilter;
        }

        $cartesianStep = $this->steps->pivotCartesian($resultFilters);
        $plan->add($cartesianStep);

        $insertQuery = $pivot->databaseInsertQuery();
        $insertStep = $this->steps->pivotInsert(
            $insertQuery,
            array(
                $firstSide->pivotKey(),
                $secondSide->pivotKey()
            ),
            $cartesianStep
        );
        
        $plan->add($insertStep);
        $queryStep = $this->steps->query($insertQuery);
        
        $plan->add($queryStep);
    }
}
