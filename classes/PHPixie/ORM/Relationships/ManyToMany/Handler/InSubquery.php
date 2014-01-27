<?php

namespace PHPixie\ORM\Relationships\ManyToMany\Handler;

class InSubquery extends PHPixie\ORM\Relationships\ManyToMany\Handler {
	
	protected $subquery_strategy = null;
	
	protected function process_add_queries($queries) {
		$plan = null;
		$insert_query = $this->db->query('insert')
											->table($config['pivot']);
		$id_union = $this->db->query('select')
										->fields(array(
											$this->db->expr($side_id),
											$config["{$opposing_side}_model_id"]
										));
		
		foreach($queries as $key => $query) {
			$query_plan = $query->plan();
			
			$id_query = $plan->current_query();
			$id_query->fields(array($config["{$opposing_side}_model_id"]));
			
			if ($key === 0) {
				$plan = $query_plan;
				$insert_query
						->batch_data(
							array(
								$config["{$side}_pivot_key"],
								$config["{$opposing_side}_pivot_key"]
							),
							$id_union
						);
				
			}else {
				$query_plan->remove_last();
				$plan->merge($query_plan);
				$id_union->union($id_query);
			}
		}
		
		$plan->add($insert_query);
		$plan->execute();
	}
	
	protected function process_add($config, $side, $opposing_side, $model, $collection) {
		$ids = $collection->field($config["{$side}_model_id"], true);
		$this->process_add_ids($config, $side, $opposing_side, $model->id(), $ids);
		
		$this->process_add_queries($collection->added_queries());
	}
}