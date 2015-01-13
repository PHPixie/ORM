<?php

namespace PHPixie\ORM\Planners\Planner\In\Strategy;

class Multiquery extends \PHPixie\ORM\Planners\Planner\In\Strategy
{
    public function in($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $subquery->fields(array($subqueryField));
        $resultStep = $this->steps->iteratorResult($subquery);
        $plan->add($resultStep);
        $placeholder = $query->getWhereBuilder()->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $subqueryField);
        $plan->add($inStep);
    }
}
