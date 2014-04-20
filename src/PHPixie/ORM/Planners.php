<?php

namespace \PHPixie\ORM;

class Planners
{
	protected $ormBulder;
    protected $planners;
	
	public function __construct($ormBuilder)
	{
		$this->ormBuilder = $ormBuilder;
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

    public function plannerInstance($name)
    {
        if (!isset($this->planners[$name])) {
			$steps = $this->ormBuilder->steps();
            $this->planners[$name] = $this->buildPlanner($name, $steps);
		}
        return $this->planners[$name];
    }

    protected function buildPlanner($name, $steps)
    {
        $class = '\PHPixie\ORM\Planners\Planner\\'.ucfirst($name);
        return new $class($steps);
    }

}
