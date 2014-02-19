<?php

namespace \PHPixie\ORM\Query\Plan;

class Result extends \PHPixie\ORM\Query\Plan {
	
	protected $orm;
	
	protected $required_plan;
	protected $result_plan;
	protected $preload_plan;
	
	public function __construct($orm) {
		$this->orm = $orm;
	}
	
	public function required_plan() {
		if ($this->required_plan === null)
			$this->required_plan = $this->orm->plan();
		return $this->required_plan;
	}
	
	public function result_plan() {
		if ($this->result_plan === null)
			$this->result_plan = $this->orm->plan();
		return $this->result_plan;
	}
	
	public function preload_plan() {
		if ($this->preload_plan === null)
			$this->preload_plan = $this->orm->plan();
		return $this->preload_plan;
	}
	
}