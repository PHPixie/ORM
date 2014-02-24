<?php

namespace PHPixie\ORM\Planners\Planner\Update;

class Field {

	protected $value_source;
	protected $value_field;
	
	public function __construct($value_source, $value_field) {
		$this->value_source = $value_source;
		$this->value_field = $value_field;
	}
	
	public function value_source() {
		return $this->value_source;
	}
	
	public function value_field() {
		return $this->value_field;
	}
}