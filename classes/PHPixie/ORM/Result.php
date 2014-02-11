<?php

namespace PHPixie\ORM;

class Result implements \Iterator {
	
	protected $current;
	
	public function __construct() {
	
	}
	
	public function as_array() {
	}
	
	public function current() {
		return $this->current;
	}
	
	public function next() {
		$this->current = $this->next_model();
	}
	
	public function valid();
	public function rewind();
	
	protected function next_model() {
		$this->loader->next();
	}
}