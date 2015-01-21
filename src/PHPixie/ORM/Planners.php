<?php

namespace PHPixie\ORM;

class Planners
{
    protected $ormBulder;
    protected $planners = array();

    public function __construct($ormBuilder)
    {
        $this->ormBuilder = $ormBuilder;
    }

    public function in()
    {
        return $this->plannerInstance('in');
    }

    public function pivot()
    {
        return $this->plannerInstance('pivot');
    }

    public function query()
    {
        return $this->plannerInstance('query');
    }
    
    public function update()
    {
        return $this->plannerInstance('update');
    }

    protected function plannerInstance($name)
    {
        if (!array_key_exists($name, $this->planners)) {
            $method = 'build'.ucfirst($name).'Planner';
            $this->planners[$name] = $this->$method();
        }

        return $this->planners[$name];
    }
    
    protected function buildInPlanner()
    {
        return new Planners\Planner\In(
            $this->ormBuilder->steps()
        );
    }
    
    protected function buildPivotPlanner()
    {
        return new Planners\Planner\Pivot(
            $this,
            $this->ormBuilder->steps()
        );
    }

    protected function buildQueryPlanner()
    {
        return new Planners\Planner\Query();
    }

    protected function buildUpdatePlanner()
    {
        return new Planners\Planner\Update(
            $this->ormBuilder->steps()
        );
    }
    
}
