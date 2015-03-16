<?php

namespace PHPixie\ORM\Planners\Planner\In\Strategy;

class Subquery extends \PHPixie\ORM\Planners\Planner\In\Strategy
{
    protected $alias = 0;
    
    public function in($query, $queryField, $subquery, $subqueryField, $plan, $logic = 'and', $negate = false)
    {
        $wrapperQuery = $query->connection()->selectQuery();
        $wrapperQuery->table($subquery, 'in'.$this->alias++);
        
        $subquery->fields(array($subqueryField));
        $query->addInOperatorCondition($queryField, $wrapperQuery, $logic, $negate, true);
    }
}
