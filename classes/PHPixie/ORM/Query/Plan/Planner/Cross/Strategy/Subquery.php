<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy;

class Subquery extends \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy {
	
	public function link() {
		$keys = array($left_key, $right_key);
		
		$query->
		$query = $this->db->query('insert', $config['pivot_connection']);
		$this->set_collection($query, $config['pivot']);
		
		
		$insert_step = $this->steps->cross_insert($query, $keys);
		
		$queries = array();
		
		$sides = array(
			'left' => array(
				'collection' => $left_collection,
				'repository' => $left_repository,
				'id_field' => $left_repository->id_field(),
				'pivot_key'  => $left_key
			),
			'right' => array(
				'collection' => $right_collection,
				'repository' => $right_repository,
				'id_field' => $right_repository->id_field(),
				'pivot_key'  => $right_key
			),
		);
		
		foreach($sides as $side_name => $side) {
		
			$queries[$side_name] = $side['repository']->collection()->query('select')
																	->fields(array($side['id_field']));
			$inner_query = null;
			foreach($side['collection']->get_ids($side['id_field'], true) as $id)
				$inner_query = $this->union(
										$inner_query, 
										$this->id_query($side['repository'], $side['id_field'], $id)
								);
			
			foreach($side['collection']->queries() as $orm_query) 
				$inner_query = $this->union(
										$inner_query,
										$this->map_orm_query($orm_query, $plan, $side['id_field'])
								);
			
			if ($inner_query === null)
				throw new \PHPixie\Exception\Mapper("No ids or queries set for pivot '{$side['pivot_key']}' field.");
			
			$queries[$side_name]->table($inner_query, "cross_insert_{$side_name}_side");
		}
		
		$insert_query = $this->db->query('insert', $pivot_connection)
												->table($pivot_table)
												->batch_insert($keys, $queries['left']->join($queries['right'], 'cross'));
												
		$plan->push($insert_query);
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