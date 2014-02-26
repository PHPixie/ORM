<?php

namespace PHPixie\ORM\Planners\Steps\Step\Query\Result\Iterators\Result;

class Data extends \PHPixie\ORM\Planners\Steps\Step\Query\Result\Iterators\Result{
	
	protected $data;
	protected $reached_end = false;
	protected $count;
	
	public function __construct($data) {
		$this->data = $data;
		$this->count = count($data);
	}
	
	public function current() {
		current($data);
	}
	
	public function key() {
		key($data);
	}
	
	public function valid() {
		return !$this->reached_end;
	}
	
	public function next() {
		if ($this->key() === $count - 1) {
			$this->reached_end = true;
		}else
			next($data);
	}
	
	public function rewind() {
		$this->reached_end = false;
		reset($data);
	}
}