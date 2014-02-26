<?php

namespace PHPixie\ORM\Iterator\Result;

class Iterator extends \PHPixie\ORM\Iterator\Result{
	
	protected $iterator;
	
	public function __construct() {
		$this->iterator = $iterator;
	}
	
	public function current() {
		return $this->iterator->current();
	}
	
	public function key() {
		return $this->iterator->key();
	}
	
	public function valid() {
		return $this->iterator->valid();
	}
	
	public function next() {
		return $this->iterator->next();
	}
	
	public function rewind() {
		return $this->iterator->rewind();
	}
}