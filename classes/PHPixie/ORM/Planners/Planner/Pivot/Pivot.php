<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Pivot;

class Pivot {

	protected $pivot_connection;
	protected $pivot;
	
	public function __construct($pivot_connection, $pivot) {
		$this->pivot_connection = $pivot_connection;
		$this->pivot = $pivot;
	}
	
}