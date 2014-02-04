<?php

namespace \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy;

class Ids extends \PHPixie\ORM\Query\Plan\Planner\Cross\Strategy {
	
	public function link() {
		$keys = array($left_key, $right_key);
		
		$query = $this->db->query('insert', $config['pivot_connection']);
		$this->set_collection($query, $config['pivot']);
		
		$insert_step = $this->steps->cross_insert($query, $keys);
		
		$insert_step->add_left_ids($left_collection->field($left_id_field) , true);
		$insert_step->add_right_ids($right_collection->field($right_id_field) , true);
		
		$collections = array(
			'left' => $left_collection,
			'right' => $right_collection,
		);
		
		foreach($collections as $side => $collection)
			foreach($collection->queries() as $query) {
				$subplan = $query->map();
				$subquery = $subplan->pop_result_query();
				$plan->prepend_plan($subplan);
				$insert_step->add_query($side, $subquery);
			}
		
		$plan->push($insert_step);
	}
}