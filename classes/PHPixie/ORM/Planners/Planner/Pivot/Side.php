<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Pivot;

class Side {

	protected $collection;
	protected $repository;
	protected $pivot_key;
	
	public function __construct($collection, $repository, $pivot_key) {
		$this->collection = $collection;
		$this->repository = $repository;
		$this->pivot_key = $pivot_key;
	}
	
}