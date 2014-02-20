<?php

namespace PHPixie\ORM\Properties\Property;

abstract class Model {
	
	protected $query;
	
	public function __construct($handler, $side, $query) {
		parent::__construct($handler, $side);
		$this->query = $query;
	}
	
	protected function property_owner() {
		return $this->query;
	}
	
	abstract protected function load();
}