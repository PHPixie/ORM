<?php

namespace PHPixie\ORM\Planners\Planner\In\Strategy;

class Multiquery extends \PHPixie\ORM\Planners\Planner\In\Strategy
{
    public function in($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $subquery->fields(array($subqueryField));
        $resultStep = $this->steps->result($subquery);
        $plan->push($resultStep);
        $placeholder = $query->getWhereBuilder()->addPlaceholder($logic, $negate);
        $inStep = $this->steps->in($placeholder, $queryField, $resultStep, $subqueryField);
        $plan->push($inStep);
    }
}