<?php

namespace PHPixie\ORM\Subquery\In;

class Multiquery extends PHPixie\ORM\Subquery\In {
	
	protected $steps;
	
	public function __construct($steps) {
		$this->steps = $steps;
	}
	
	public function add_subquery_condition($query, $logic, $negated, $field, $subquery) {
		$condition = $query->get_builder('where')
				->add_placeholder($logic, $negated);
				
		$step = $this->steps->id_subquery($subquery, $condition, $key_field);
		$plan->prepend_step($step);
	}
	
}