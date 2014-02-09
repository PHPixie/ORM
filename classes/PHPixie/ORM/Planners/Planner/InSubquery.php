<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class InSubquery extends \PHPixie\ORM\Query\Plan\Planner{
	
	public function add_subquery_condition($query_repository, $subquery_repository, $query, $subquery, $field, $logic, $negated, $plan) {
		$strategy = $this->select_strategy($query_repository, $subquery_repository);
		$strategy->add_subquery_condition($query, $subquery, $field, $logic, $negated, $plan);
	}
	
	protected function select_strategy($query_repository, $subquery_repository) {
		if ($query_repository->connection() instanceof PHPixie\DB\Driver\PDO\Connection 
			&& $query_repository->connection_name() === $subquery_repository->connection_name()) {
			return $this->strategy('condition');
		}
		
		return $this->strategy('multiquery');
	}
	
	protected function build_strategy($name) {
		return $this->planner->in_strategy($name);
	}
}