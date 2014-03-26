<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy;

class Ids extends \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy
{
    public function linkPlan($leftCollection, $rightCollection, $leftRepository, $rightRepository, $leftPivotKey, $rightPivotKey, $plan)
    {
        $query = $this->db->query('insert', $config['pivot_connection']);
        $this->setCollection($query, $config['pivot']);

        $insertStep = $this->steps->crossInsert($query, array($leftKey, $rightKey));
        $this->addCollection($leftCollection, 'left', $leftRepository->idField(), $insertStep);
        $this->addCollection($rightCollection, 'right', $rightRepository->idField(), $insertStep);

        $plan->push($insertStep);

        return $plan;
    }

    public function unlinkPlan($leftCollection, $rightCollection, $leftRepository, $rightRepository, $leftPivotKey, $rightPivotKey, $plan)
    {
        $query = $this->db->query('insert', $config['pivot_connection']);
        $this->setCollection($query, $config['pivot']);

        $deleteStep = $this->steps->crossDelete($query, array($leftKey, $rightKey));
        $this->addCollection($leftCollection, 'left', $leftRepository->idField(), $deleteStep);
        $this->addCollection($rightCollection, 'right', $rightRepository->idField(), $deleteStep);

        $plan->push($deleteStep);

        return $plan;
    }

    protected function addCollection($collection, $side, $idField, $crossStep)
    {
        $step->addIds($side, $collection->field($idField), true);
        foreach ($collection->queries() as $query) {
            $subplan = $query->map();
            $subquery = $subplan->popResultQuery();
            $plan->prependPlan($subplan);
            $insertStep->addQuery($side, $subquery);
        }
    }
}
