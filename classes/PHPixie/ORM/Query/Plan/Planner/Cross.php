<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Cross extends \PHPixie\ORM\Query\Plan\Planner{
	
	public function link($left_repository, $right_repository, $pivot_connection, $left_collection, $right_collection) {
		$strategy = $this->select_strategy($left_repository, $right_repository, $pivot_connection);
		$strategy->link($left_repository, $right_repository, $pivot_connection, $left_collection, $right_collection);
	}
	
	protected function select_strategy($query_repository, $subquery_repository) {
		if ($query_repository->connection() instanceof PHPixie\DB\Driver\PDO\Connection 
			&& $query_repository->connection_name() === $subquery_repository->connection_name()) {
			return $this->strategy('condition');
		}
		
		return $this->strategy('multiquery');
	}
	
	protected function build_strategy($name) {
		return $this->planner->cross_strategy($name);
	}
}