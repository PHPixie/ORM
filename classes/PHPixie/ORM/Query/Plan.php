<?php

namespace \PHPixie\ORM\Query;

class Plan {
	
	protected $steps = array();
	
	
	public function append_plan($plan) {
		$this->steps = array_merge($this->steps, $plan->steps());
	}
	
	public function steps() {
		return $this->steps();
	}
	
	public function execute() {
		foreach($this->steps as $step) {
			$step->execute();
		}
	}
	
}