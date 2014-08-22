<?php

namespace PHPixie\ORM\Planners\Planner\Pivot\Strategy;

class Multiquery extends \PHPixie\ORM\Planners\Planner\Pivot\Strategy
{
    public function link($pivot, $firstSide, $secondSide, $plan)
    {
        $resultSteps = array();
        foreach (array($firstSide, $secondSide) as $side) {
            $idQuery = $this->idQuery($side, $plan);
            $resultStep = $this->steps->result($query);
            $plan->add($resultStep);
            $resultSteps[] = $resultStep;
        }

        $cartesianStep = $this->steps->pivotCartesian($resultSteps);
        $plan->add($cartesianStep);

        $insertStep = $this->steps->pivotInsert(
                                                    $pivot->connection,
                                                    $pivot->pivot,
                                                    array(
                                                        $firstSide->pivotKey,
                                                        $secondSide->pivotKey
                                                    ),
                                                    $cartesianStep
                                                );
        $plan->add($insertStep);
    }
}
