<?php

namespace PHPixie\ORM\Result;

abstract class Iterator impelements \Iterator{
	
	protected $preloaders;
	protected $current;
	
	public function __construct($preloaders) {
		$this->preloaders = $preloaders;
	}
	
	public function current() {
		if ($this->current_model === null)
			$this->current_model();
		
		return $this->current_model;
	}

	public abstract function key();
	public abstract function valid();
	public abstract function next();
	public abstract function rewind();
	protected abstract function current_model();
}