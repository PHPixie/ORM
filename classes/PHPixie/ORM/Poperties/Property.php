<?php

namespace PHPixie\ORM\Properties;

abstract class Property {
	
	protected $handler;
	protected $side;
	
	public function __construct($handler, $side) {
		$this->handler = $handler;
		$this->side = $side;
	}
	
	public function query() {
		return $this->handler->query($this->side, $this->property_owner());
	}
	
	protected abstract function property_owner();
}