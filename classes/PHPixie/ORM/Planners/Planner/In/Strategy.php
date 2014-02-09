<?php

namespace \PHPixie\ORM\Query\Plan\Planner\InSubquery;

abstract class Strategy {
	
	protected $steps;
	
	public function __construct($steps) {
		$this->steps = $steps;
	}
}
