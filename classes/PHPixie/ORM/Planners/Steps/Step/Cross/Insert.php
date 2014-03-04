<?php

namespace PHPixie\ORM\Query\Plan\Step\Cross;

class Insert extends \PHPixie\ORM\Query\Plan\Step\Query {
	
	protected $keys;
	protected $result_steps;
	
	public function __construct($query, $keys, $result_steps) {
		parent::__construct($query);
		$this->keys = $keys;
		$this->result_steps = $result_steps;
	}
	
	public function execute() {
		$results = array();
		foreach($this->result_steps as $step) {
			$result = array();
			foreach($step->result() as $row)
				$result[] = array_values((array) $row);
			$results[] = $result;
		}
		$rows = $this->cartesian($results);
		$this->query->batch_insert($this->keys, $rows);
		parent::execute();
	}
	
	protected function cartesian($arrays) {
		$left = array_shift($arrays);
		$right = count($arrays) === 1 ? current($array) : $this->cartesian($arrays);
		$result = array();
		foreach($left as $l)
			foreach($right as $r)
				$result[] = array_merge($l, $r);
		return $result;
	}
}