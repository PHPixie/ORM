<?php

namespace PHPixie\ORM\Query\Plan\Step;

class In{
	
	protected $placeholder;
	protected $placeholder_field;
	protected $result_step;
	protected $result_field;
	
	public function __construct($placeholder, $placeholder_field, $result_step, $result_field) {
		$this->placeholder = $placeholder;
		$this->placeholder_field = $placeholder_field;
		$this->result_step = $result_step;
		$this->result_field = $result_field;
	}
	
	public function execute() {
		$values = $this->result_step->result()->get_column($result_field);
		$placeholder->where($this->placeholder_field, 'in', $values);
	}
}