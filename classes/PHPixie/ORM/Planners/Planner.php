<?php

namespace \PHPixie\ORM\Query\Plan;

abstract class Planner {
	
	protected $strategies = array();
	
	protected function strategy($name) {
		if (!isset($this->strategies[$name]))
			$this->strategies[$name] = $this->build_strategy($name);
		return $this->strategies[$name];
	}
	
	abstract protected function build_strategy($name);
}