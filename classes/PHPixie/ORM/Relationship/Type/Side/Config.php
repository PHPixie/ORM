<?php

namespace PHPixie\ORM\Relationship\Side;

abstract class Config {
	
	public function __construct($inflector, $config) {
		$this->process_config($config, $inflector);
	}
	
	public function get($key) {
		return $this->$key;
	}
	
	protected abstract function process_config($config, $inflector);
}