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

    /**
     * @return Planners\Planner\Document
     */
    public function document()
    {
        return $this->plannerInstance('document');
    }

    /**
     * @return Planners\Planner\In
     */
    public function in()
    {
        return $this->plannerInstance('in');
    }

    /**
     * @return Planners\Planner\Pivot
     */
    public function pivot()
    {
        return $this->plannerInstance('pivot');
    }

    /**
     * @return Planners\Planner\Query
     */
    public function query()
    {
        return $this->plannerInstance('query');
    }

    /**
     * @return Planners\Planner\Update
     */
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
    
    protected function buildDocumentPlanner()
    {
        return new Planners\Planner\Document();
    }
    
    protected function buildInPlanner()
    {
        return new Planners\Planner\In(
            $this->ormBuilder->conditions(),
            $this->ormBuilder->mappers(),
            $this->ormBuilder->steps()
        );
    }
    
    protected function buildPivotPlanner()
    {
        return new Planners\Planner\Pivot(
            $this,
            $this->ormBuilder->steps(),
            $this->ormBuilder->database()
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
