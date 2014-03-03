<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Pivot extends \PHPixie\ORM\Query\Plan\Planner{
	
	public function link($pivot, $first_side, $second_side, $plan) {
		$link_method = $this->link_method($pivot, $first_side, $second_side);
		$this->$link_method($pivot, $first_side, $second_side, $plan);
	}
	
	protected function link_method($pivot, $first_side, $second_side) {
		$p_conn = $pivot->connection();
		$f_conn = $first_side-> repository->connection();
		$s_conn = $second_side-> repository->connection();
		
		if ($p_conn === $f_conn && $f_conn === $s_conn && $p_conn instanceof PHPixie\DB\Driver\PDO\Connection)
			return 'link_subquery';
		return 'link_multiquery';
	}
	
	protected function link_subquery() {
		$in_planner = $this->planners->in();
		
		$queries = array();
		foreach(array($first_side, $second_side) as $side) {
			$repository = $side->repository();
			$id_field = $repository()->id_field();
			$query = $repository->db_query()->fields(array($id_field));
			$in_planner->collection('or', false, $query, $id_field, $collection, $id_field, $plan);
			$queries[] = $query;
		}
		
		
	}
	
	public function pivot($connection, $pivot) {
		return new Pivot\Pivot($connection, $pivot);
	}
	
	public function side($collection, $id_field, $pivot_key) {
		return new Pivot\Side($collection, $id_field, $pivot_key);
	}
}