<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy;

class Subquery extends \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy {
	
	public function link_plan($left_collection, $right_collection, $left_repository, $right_repository, $left_pivot_key, $right_pivot_key, $plan) {
		$left_query = $this->collection_query($left_collection, $left_repository, $left_pivot_key, $plan);
		$right_query = $this->collection_query($right_collection, $right_repository, $right_pivot_key, $plan);
		
		$query = $this->db->query('insert', $pivot_connection)
												->table($pivot_table)
												->batch_insert(
													array($left_key, $right_key),
													$left_query->join($right_query, 'cross')
												);
												
		$plan->push($this->steps->query($query));
		return $plan;
	}
	
	public function unlink_plan($left_collection, $right_collection, $left_repository, $right_repository, $left_pivot_key, $right_pivot_key, $plan) {
		$left_query = $this->collection_query($left_collection, $left_repository, $left_pivot_key, $plan);
		$right_query = $this->collection_query($right_collection, $right_repository, $right_pivot_key, $plan);
		
		$query = $this->db->query('delete', $pivot_connection)
												->table($pivot_table)
												->where($left_key, 'in', $left_query)
												->where($right_key, 'in', $right_query)
												
		$plan->push($this->steps->query($query));
		return $plan;
	}
	
	protected function collection_query($collection, $repository, $pivot_key, $plan) {
		$id_field = $repository->id_field();
		$query = $collection()->query('select')
												->fields(array($id_field));
		$inner_query = null;
		foreach($collection->get_ids($id_field, true) as $id)
			$inner_query = $this->union(
									$inner_query, 
									$this->id_query($repository, $id_field, $id)
								);
			
		foreach($collection->queries() as $orm_query) 
			$inner_query = $this->union(
									$inner_query,
									$this->map_orm_query($orm_query, $plan, $id_field)
								);
			
		if ($inner_query === null)
			throw new \PHPixie\Exception\Mapper("No ids or queries set for pivot '{$pivot_key}' field.");
			
		$query->table($inner_query, "cross_{$pivot_key}");
		return $query;
	}
	
	protected function union($query, $subquery) {
		if ($query === null)
			return $subquery;
		return $query->union($subquery);
	}
	
	protected function id_query($repository, $id_field, $id) {
		return $repository->collection()->query('select')
													->fields(array(
														$id_field => $this->db->expr($id)
													));
	}
	
	protected function map_orm_query($orm_query, $plan, $id_field) {
		$subplan = $orm_query->map();
		$subquery = $subplan->pop_result_query();
		$plan->prepend_plan($subplan);
		$subquery->fields(array($id_field));
		return $subquery;
	}
}