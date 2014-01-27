<?php

namespace PHPixe\ORM\Relationships\ManyToMany;

class Handler{
	
	protected $id_subquery_strategy;
	
	public function relationship_side_query($side, $condition, $property) {
		$config = $this->get_config($left_condition->model_name(), $property);
		return $this->orm->query($config[$side.'_model'])
									->related($config[$side.'_to_left_property'], $condition);
	}
	
	protected get_pivot_side_subquery($side, $opposing_side, $config, $opposing_query, $alias) {
	
		$query = $this->db->query('select')
						->fields(array($config['pivot_'.$side.'_key']))
						->from($config['pivot_table'], $alias);
						
		$this->add_subquery_condition($query, $group->logic, $group->negated(), $config['owner_id_field'], $opposing_query);
		return $query;
	}
	
	protected function get_side_ids_subquery($side, $config, $conditions, $alias) {
		$query = $this->db->query('select')
						->fields(array($config[$side.'_id_field']))
						->from($config[$side.'_table'], $alias);
						
		$this->mapper->add_conditions($query, $conditions);
		return $query;
	}
	
	protected function process_side_relationship($side, $opposing_side, $config, $query, $group, $relationship, $plan) {
		
		$opposing_subquery = $this->get_side_ids_subquery($opposing_side, $config, $conditions, $alias);
		$pivot_subquery = get_pivot_side_subquery($side, $config, $conditions, $alias);
		$this->id_strategy->add_condition($query, 'and', false, $config["pivot_{$opposing_side}_key"], $opposing_subquery);
		$this->id_strategy->add_condition($query, $group->logic, $group->negated(), $config["{$side}_id_field"], $pivot_subquery);
	}
	
	public function process_right_relationship($config, $query, $group, $relationship, $plan) {
		$this->process_side_relationship('right', 'left', $config, $query, $group, $relationship, $plan);
	}
	
	public function process_left_relationship($config, $query, $group, $relationship, $plan) {
		$this->process_side_relationship('left', 'right', $config, $query, $group, $relationship, $plan);
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