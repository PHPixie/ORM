<?php

namespace \PHPixie\ORM\Query;

class Plan {
	
	protected $queries;
	protected $condition_map;
	
	
	public function pop() {
		return array_pop($this->steps);
	}
	
	public function unshift($step) {
		array_unshift($this->steps, $step);
	}
	
	public function shift() {
		return array_shift($this->steps);
	}
	
	public function prepend_plan($plan) {
		$this->steps = array_merge($plan->steps(), $this->steps);
	}
	
	public function steps() {
		return $this->steps();
	}
	
	public function execute() {
		foreach($this->steps as $step) {
			$step->execute();
		}
		
		if ($step instanceof \PHPixie\ORM\Query\Plan\Step\ResultQuery)
			$this->result = $step->result();
	}
	
	public function result() {
		return $this->result();
	}
}