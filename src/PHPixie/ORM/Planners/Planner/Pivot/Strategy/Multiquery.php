<?php

namespace \PHPixie\ORM\Planners\Planner\Pivot\Strategy;

class Multiquery extends \PHPixie\ORM\Planners\Planner\Pivot\Strategy
{
    public function link($pivot, $firstSide, $secondSide, $plan) {
        $resultSteps = array();
        foreach(array($firstSide, $secondSide) as $side) {
            $repository = $side->repository;
            $sidePlan = $repository->query()->in($side-> collection)->findPlan();
            $plan->appendPlan($sidePlan->requiredPlan());
            $query = $this->resultStep()->query();
            $query->fields($repository->idField);
            $resultStep = $this->steps->result($query);
            $plan->add($resultStep);
            $resultSteps[] = $resultStep;
        }
        
        $crossStep = $this->steps->cross($pivot, $firstSide, $resultSteps[0], $secondSide, $resultSteps[1]);
        
        
    }
}
