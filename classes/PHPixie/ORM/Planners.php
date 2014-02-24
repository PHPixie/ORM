<?php

namespace \PHPixie\ORM;

class Planners {
	
	protected $steps;
	
	protected function steps() {
		if ($this->steps === null)
			$this->steps = $this->build_steps();
	}
	
	protected function build_steps() {
		return new \PHPixie\ORM\Planners\Steps();
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
	
	public function build_update_field($value_source, $value_field) {
		return new \PHPixie\ORM\Planners\Planner\Update\Field($value_source, $value_field);
	}
	
	public function planner_instance($name) {
		if (!isset($this->instances[$name]))
			$this->instances[$name] = $this->build_planner($name);
		
		return $this->instances[$name];
	}
	
	protected function build_planner($name) {
		$class = '\PHPixie\ORM\Planners\Planner\\'.ucfirst($name);
		return new $class($this->steps());
	}
	
}