<?php

namespace PHPixe\ORM\Relationships\ManyToMany;

class Handler{
	
	protected $id_subquery_strategy;
	
	public function relationship_side_query($side, $condition, $property) {
		$config = $this->get_config($left_condition->model_name(), $property);
		return $this->orm->query($config[$side.'_model'])
									->related($config[$side.'_to_left_property'], $condition);
	}
	
	protected get_linker_side_subquery($side, $opposing_side, $config, $opposing_query, $alias) {
	
		$query = $this->db->query('select')
						->fields(array($config['linker_'.$side.'_key']))
						->from($config['linker_table'], $alias);
						
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
		$linker_subquery = get_linker_side_subquery($side, $config, $conditions, $alias);
		$this->id_strategy->add_condition($query, 'and', false, $config["linker_{$opposing_side}_key"], $opposing_subquery);
		$this->id_strategy->add_condition($query, $group->logic, $group->negated(), $config["{$side}_id_field"], $linker_subquery);
	}
	
	public function process_right_relationship($config, $query, $group, $relationship, $plan) {
		$this->process_side_relationship('right', 'left', $config, $query, $group, $relationship, $plan);
	}
	
	public function process_left_relationship($config, $query, $group, $relationship, $plan) {
		$this->process_side_relationship('left', 'right', $config, $query, $group, $relationship, $plan);
	}
	
	protected function add_to_side($side, $config, $model, $items) {
		$model_name = $config["{$side}_model"];
		
		
		
		$keys = array($config["linker_{$side}_key"], $config["linker_{$opposing_side}_key"]);
		$data = array();
		foreach($ids as $id)
			$data[] = array($model->id(), $id);
		
		
		$this->db->query('insert', $config['linker_connection'])
																->table($config['linker_table'])
																->batch_data($keys, $data)
																->execute();
	}
	
	public function add_to_left($model, $property ,$items) {
		$this->add_to_side('left', $config, $query, $group, $relationship, $plan);
	}
}