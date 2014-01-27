<?php

namespace PHPixie\ORM;

abstract class Relationship {
	
	protected $mapper;
	
	protected function mapper() {
		if ($this->mapper === null) 
			$this->mapper = $this->build_mapper();
		
		return $this->mapper;
	}
	
	public function register($config) {
		$this->mapper()->map_properties($config);
	}
	
	public function handled_properties() {
		return $this->mapper()->handled_properties();
	}
	
	abstract protected function build_mapper();
	abstract protected function build_property($type);
}