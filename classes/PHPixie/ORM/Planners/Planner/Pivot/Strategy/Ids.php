<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy;

class Ids extends \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy {
	
	public function link_plan($left_collection, $right_collection, $left_repository, $right_repository, $left_pivot_key, $right_pivot_key, $plan) {
		$query = $this->db->query('insert', $config['pivot_connection']);
		$this->set_collection($query, $config['pivot']);
		
		$insert_step = $this->steps->cross_insert($query, array($left_key, $right_key));
		$this->add_collection($left_collection, 'left', $left_repository->id_field(), $insert_step);
		$this->add_collection($right_collection, 'right', $right_repository->id_field(), $insert_step);
		
		$plan->push($insert_step);
		return $plan;
	}
	
	public function unlink_plan($left_collection, $right_collection, $left_repository, $right_repository, $left_pivot_key, $right_pivot_key, $plan) {
		$query = $this->db->query('insert', $config['pivot_connection']);
		$this->set_collection($query, $config['pivot']);
		
		$delete_step = $this->steps->cross_delete($query, array($left_key, $right_key));
		$this->add_collection($left_collection, 'left', $left_repository->id_field(), $delete_step);
		$this->add_collection($right_collection, 'right', $right_repository->id_field(), $delete_step);
		
		$plan->push($delete_step);
		return $plan;
	}
	
	protected function add_collection($collection, $side, $id_field, $cross_step) {
		$step->add_ids($side, $collection->field($id_field), true);
		foreach($collection->queries() as $query) {
			$subplan = $query->map();
			$subquery = $subplan->pop_result_query();
			$plan->prepend_plan($subplan);
			$insert_step->add_query($side, $subquery);
		}
	}
}