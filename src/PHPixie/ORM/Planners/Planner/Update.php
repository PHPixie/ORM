<?php

namespace PHPixie\ORM\Planners\Planner;

class Update extends \PHPixie\ORM\Planners\Planner
{
    protected $steps;
    
    public function __construct($steps)
    {
        $this->steps = $steps;
    }
    
    public function result($updateQuery, $map, $resultStep, $plan)
    {
        $mapStep = $this->steps->updateMap($updateQuery, $map, $resultStep);
        $plan->add($mapStep);
    }
    
    public function subquery($updateQuery, $map, $subquery, $plan)
    {
        $fields = $this->requiredFields($map);
        $subquery->fields($fields);
        $resultStep = $this->steps->iteratorResult($subquery);
        $plan->add($resultStep);
        $this->result($updateQuery, $map, $resultStep, $plan);
    }
    
    protected function requiredFields($map)
    {
        return array_values(array_unique($map));
    }    
}