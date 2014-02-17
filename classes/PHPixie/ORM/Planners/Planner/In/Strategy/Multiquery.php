<?php

namespace \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy;

class Multiquery extends \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy {
	
	public function add_subquery_condition($builder, $builder_field, $subquery, $plan) {
		$placeholder = $builder->placeholder();
		$step = $this->steps->in_subquery($subquery, $placeholder, 'or', false, $field);
		$plan->prepend_step($step);
	}
}
