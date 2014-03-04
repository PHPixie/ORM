<?php

namespace \PHPixie\ORM\Query\Plan\Planner;

class Pivot extends \PHPixie\ORM\Query\Plan\Planner{
	
	public function link($pivot, $first_side, $second_side, $plan) {
		$link_method = $this->link_method($pivot, $first_side, $second_side);
		$this->$link_method($pivot, $first_side, $second_side, $plan);
	}
	
	public function unlink($pivot, $first_side, $plan, $second_side = null) {
		$delete_query = $pivot->query('delete');
		$sides = array($first_side);
		if ($second_side !== null)
			$sides[] = $second_side;
		foreach($sides as $side)
			$this->planners->in()->->collection(
													$delete_query,
													$side->pivot_key(),
													$side->collection(),
													$side->id_field(),
													$plan,
													'and',
													false
												);
		$step = $this->steps->query($delete_query);
		$plan->push($step);
	}
	
	protected function link_method($pivot, $first_side, $second_side) {
		$p_conn = $pivot->connection();
		$f_conn = $first_side-> repository->connection();
		$s_conn = $second_side-> repository->connection();
		
		if ($p_conn === $f_conn && $f_conn === $s_conn && $p_conn instanceof PHPixie\DB\Driver\PDO\Connection)
			return 'link_pdo';
		return 'link_generic';
	}
	
	protected function link_pdo($pivot, $first_side, $second_side, $plan) {
		$first_query = $this->id_query($first_side, $plan);
		$second_query = $this->id_query($first_side, $plan);
		
		$cross_query = $pivot->connection()->query('select')
												->fields(array(
													'first_side.'$sides[0]['id_field'], 
													'second_side.'$sides[1]['id_field']
												))
												->table($queries[0], 'first_side')
												->join($queries[1], 'second_side', 'cross');
		$insert_query = $pivot->query('insert')
									->on_duplicate_key('update')
									->batch_data(array(
										$first_side->pivot_key(),
										$second_side->pivot_key()
									), $cross_query);
		$step = $this->steps->query($insert_query);
		$plan->push($step);
	}
	
	protected function link_generic($pivot, $first_side, $second_side, $plan) {
		$first_query = $this->id_query($first_side, $plan);
		$second_query = $this->id_query($first_side, $plan);
		
		$first_step = $this->steps->result($first_query);
		$second_step = $this->steps->result($second_query);
		
		$insert_query = $pivot->query('insert')
							->on_duplicate_key('update');
		$keys = array(
			$first_side->pivot_key(),
			$second_side->pivot_key()
		);
		
		$step = $this->steps->pivot_insert($insert_query, $keys, array($first_step, $second_step));
		$plan->push($step);
		
	}
	
	
	
	
	protected function id_query($side) {
		$repository = $side->repository();
		$id_field = $repository()->id_field();
		$query = $repository->db_query()->fields(array($id_field));
		$this->planners->in()->collection($query, $id_field, $collection, $id_field, $plan, 'and', false);
		return $query;
	}
	
	public function pivot($connection, $pivot) {
		return new Pivot\Pivot($connection, $pivot);
	}
	
	public function side($collection, $id_field, $pivot_key) {
		return new Pivot\Side($collection, $id_field, $pivot_key);
	}
}