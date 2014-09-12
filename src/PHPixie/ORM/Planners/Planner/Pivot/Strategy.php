<?php

namespace PHPixie\ORM\Planners\Planner\Pivot;

abstract class Strategy
{
    protected $planners;
    protected $steps;

    public function __construct($planners, $steps)
    {
        $this->planners = $planners;
        $this->steps    = $steps;
    }

    public function unlink($pivot, $firstSide, $secondSide, $plan)
    {
        $deleteQuery = $pivot->connection->query('delete');
        $this->queryPlanner->setSource($deleteQuery, $pivot->pivot);

        foreach (array($firstSide, $secondSide) as $side) {
            $idQuery = $this->idQuery($side, $plan);
            $idField = $side->repository->idField();
            $this->planners->in->subquery($deleteQuery, $side->pivotKey, $idQuery, $idField, $plan);
        }

        $deleteStep = $this->steps->query($deleteQuery);
        $plan->add($deleteStep);
    }

    protected function idQuery($side, $plan)
    {
        $repository = $side->repository;
        $sidePlan = $repository->query()->in($side->items)->findPlan();
        $plan->appendPlan($sidePlan->requiredPlan());
        $query = $sidePlan->resultStep()->query();
        $query->fields($repository->idField);

        return $query;
    }

    abstract public function link($pivot, $firstSide, $secondSide, $plan);
}
