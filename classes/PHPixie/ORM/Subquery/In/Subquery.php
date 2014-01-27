<?php

namespace PHPixie\ORM\Subquery\In;

class Subquery extends PHPixie\ORM\Subquery\In {
	
	public function add_subquery_condition($query, $logic, $negated, $field, $subquery) {
		$query->get_builder('where')
				->add_operator_condition($logic, $negated, $field, 'in', array($subquery));
	}
	
}