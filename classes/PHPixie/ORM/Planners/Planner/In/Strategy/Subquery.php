<?php

namespace \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy;

class Subquery extends \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy {
	
	public function add_subquery_condition($builder, $builder_field, $subquery, $plan) {
		$builder->add_operator_condition('or', false, $builder_field, 'in', $subquery);
	}
}
