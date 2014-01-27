<?php

namespace PHPixie\ORM\Conditions\Condition;

class Relationship extends \PHPixie\ORM\Conditions\Condition {
	public $property_name;
	public $value;
	
	public function __construct($property_name, $value) {
		$this->property_name = $property_name;
		$this->value = $value;
	}
}