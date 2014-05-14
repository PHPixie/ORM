<?php

namespace PHPixie\ORM\Planners\Planner\In\Strategy;

class Subquery extends \PHPixie\ORM\Planners\Planner\In\Strategy
{
    public function in($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $subquery->fields(array($subqueryField));
        $query->getWhereBuilder()->addOperatorCondition($logic, $negate, $queryField, 'in', $subquery);
    }
}
