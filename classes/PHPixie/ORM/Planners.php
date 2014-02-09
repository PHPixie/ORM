<?php

namespace \PHPixie\ORM;

class Planners {
	
	protected $steps;
	
	public function __construct($steps) {
		$this->steps = $steps;
	}
	
	public function embed() {
		return $this->planner_instance('embed');
	}
	
	public function pivot() {
		return $this->planner_instance('pivot');
	}
	
	public function in() {
		return $this->planner_instance('in');
	}
	
	
	public function planner_instance($name) {
		if (!isset($this->instances[$name]))
			$this->instances[$name] = $this->build_planner($name);
		
		return $this->instances[$name];
	}
	
	protected function build_planner($name) {
		$class = '\PHPixie\ORM\Planners\Planner\\'.ucfirst($name);
		return new $class($this->steps);
	}
	
}