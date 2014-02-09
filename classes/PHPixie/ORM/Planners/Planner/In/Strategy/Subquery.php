<?php

namespace \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy;

class Subquery extends \PHPixie\ORM\Query\Plan\Planner\InSubquery\Strategy {
	
	public function add_subquery_condition($query, $subquery, $field, $logic, $negated, $plan) {
		$query->get_builder('where')
				->add_operator_condition($logic, $negated, $field, 'in', array($subquery));
	}
}
