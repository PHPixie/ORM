<?php

namespace PHPixie\ORM\Subquery;

class In {
	
	abstract public function add_subquery_condition($query, $logic, $negated, $field, $subquery);
	
}