<?php

namespace PHPixe\ORM\Relationships;

class Handler{
	
	protected $relationship;
	
	public function __construct($relationship) {
		$this->relationship = $relationship;
	}
	
	public function get_config($model, $property) {
		return $this->relationship->config($model, $property);
	}
	
}