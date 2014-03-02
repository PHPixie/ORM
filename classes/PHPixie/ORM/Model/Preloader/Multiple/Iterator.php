<?php

namespace PHPixie\ORM\Model\Preloader\Multiple;

class Iterator extends \PHPixie\ORM\Model\Iterator implements \Countable {

	protected $preloader;
	protected $ids;
	protected $current_model;
	
	public function __construct($preloader, $ids) {
		$this->preloader = $preloader;
		$this->ids = $ids;
		$this->count = count($ids);
	}

	public function current() {
		if (!$this->valid())
			return null;
		
		if ($this->current_model === null)
			$this->current_model = $this->preloader->get_model(current($ids));
		
		return $this->current_model;
	}
	
	public function key() {
		key($this->ids);
	}
	
	public function valid() {
		return !$this->reached_end;
	}
	
	public function next() {
		if ($this->key() === $count - 1) {
			$this->reached_end = true;
		}else{
			next($this->ids);
			$this->current_model = null;
		}
	}
	
	public function rewind() {
		$this->reached_end = false;
		reset($this->ids);
	}
	
	public function count() {
		return $this->count;
	}
}