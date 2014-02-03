<?php

namespace PHPixe\ORM\Relationships\ManyToMany;

class Handler{
	
	protected $id_subquery_strategy;
	
	public function relationship_side_query($side, $condition, $property) {
		$config = $this->get_config($left_condition->model_name(), $property);
		return $this->orm->query($config[$side.'_model'])
									->related($config[$side.'_to_left_property'], $condition);
	}
	
	protected function process_add_ids($config, $side, $opposing_side, $side_id, $ids) {
		$keys = array(
					$config["{$side}_pivot_key"], 
					$config["{$opposing_side}_pivot_key"]
				);
		
		$values = array();
		foreach($ids as $id)
			$values[] = array($side_id, $id);
		
		$this->db->query('insert', $config['pivot_connection']);
															->target($config['pivot'])
															->batch_data($keys, $values)
															->execute();
	}
	
	public function link_items_plan($side, $side_collection, $opposing_collection, $config) {
		$plan = $this->orm->query_plan();
		
		$opposing_side = $side === 'left' ? 'right' : 'left';
		$keys = array(
					$config["{$side}_pivot_key"], 
					$config["{$opposing_side}_pivot_key"]
				);
		
		$query = $this->db->query('insert', $config['pivot_connection']);
		$this->set_collection($query, $config['pivot']);
		
		$insert_step = $this->steps->cross_insert($query, $keys);
		
		$side_id_field = $config["{$side}_repo"]->id_field();
		$opposing_id_field = $config["{$opposing_side}_repo"]->id_field();
		
		$insert_step->add_left_ids($side_collection->field($side_id_field) , true);
		$insert_step->add_right_ids($opposing_collection->field($opposing_id_field) , true);
		
		$collections = array(
			'left' => $side_collection,
			'right' => $opposing_collection,
		);
		
		foreach($collections as $side => $collection)
			foreach($collection->queries() as $query) {
				$subplan = $query->map();
				$subquery = $subplan->pop_result_query();
				$plan->prepend_plan($subplan);
				$insert_step->add_query($side, $subquery);
			}
		
		$plan->push($insert_step);
		return $plan;
	}
	
	protected function process_add($config, $side, $opposing_side, $model, $collection) {
		$ids = $collection->field($config["{$side}_model_id"]);
		$this->process_add_ids($config, $side, $opposing_side, $model->id(), $ids);
	}
	
	protected function process_remove($config, $side, $opposing_side, $model, $collection) {
		$query = $this->db->query('delete', $config['pivot_connection']);
															->target($config['pivot']);
		
		$side_id = $model->id();
		$ids = $collection->field($config["{$side}_model"]);
		
		foreach($ids as $id) {
			$query->start_group('or')
						->where($config["{$side}_pivot_key"], $side_id)
						->where($config["{$opposing_side}_pivot_key"], $id)
					->end_group();
		}
		
		$query->execute();
	}
	
	public function add($model, $property, $side, $items) {
		$confoig = $this->get_config($model->model_name(), $property);
		$opposing_side = $this->opposing_side($side);
		$collection = $this->orm->collection($config["{$side}_model"]);
		$collection->add($items);
		$this->process_add($config, $side, $opposing_side, $model, $collection);
	}
}