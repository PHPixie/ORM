<?php

namespace \PHPixie\ORM;

class Planners
{
    protected $steps;

    protected function steps()
    {
        if ($this->steps === null)
            $this->steps = $this->buildSteps();
    }

    protected function buildSteps()
    {
        return new \PHPixie\ORM\Planners\Steps();
    }

    public function embed()
    {
        return $this->plannerInstance('embed');
    }

    public function pivot()
    {
        return $this->plannerInstance('pivot');
    }

    public function in()
    {
        return $this->plannerInstance('in');
    }

    public function buildUpdateField($valueSource, $valueField)
    {
        return new \PHPixie\ORM\Planners\Planner\Update\Field($valueSource, $valueField);
    }

    public function plannerInstance($name)
    {
        if (!isset($this->instances[$name]))
            $this->instances[$name] = $this->buildPlanner($name);

        return $this->instances[$name];
    }

    protected function buildPlanner($name)
    {
        $class = '\PHPixie\ORM\Planners\Planner\\'.ucfirst($name);

        return new $class($this->steps());
    }

}
