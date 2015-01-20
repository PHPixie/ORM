<?php

namespace PHPixie\ORM\Planners\Planner\In;

abstract class Strategy
{
    abstract public function in($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false);
}
