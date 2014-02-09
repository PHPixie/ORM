<?php

namespace \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy;

class Multiquery extends \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy {
	
	public function add_subquery_condition($query, $subquery, $field, $logic, $negated, $plan) {
		$placeholder = $query->get_builder('where')
				->placeholder($logic, $negated);
				
		$step = $this->steps->in_subquery($subquery, $placeholder, $field);
		$plan->prepend_step($step);
	}
}
