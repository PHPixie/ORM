<?php

namespace PHPixe\ORM\Relationships\OneToMany;

class Handler{
	
	protected $id_subquery_strategy;
	
	public function items_query($owner_condition, $property) {
		$config = $this->get_config($owner_condition->model_name(), $property);
		return $this->orm->query($config['item_model'])
									->related($config['item_owner_property'], $owner_condition);
	}
	
	public function owner_query($item_condition, $property) {
		$config = $this->get_config($item_condition->model, $property);
		return $this->orm->query($config['owner_model'])
									->related($config['owner_items_property'], $item_condition);
	}
	
	protected function get_owner_ids_subquery($params, $conditions, $alias) {
		$query = $this->db->query('select')
						->fields(array($config['item_key']))
						->from($config['items_table'], $alias);
						
		$this->mapper->add_conditions($query, $conditions);
		return $query;
	}
	
	protected function get_item_ids_subquery($params, $conditions, $alias) {
		$query = $this->db->query('select')
						->fields(array($config['owner_id_field']))
						->from($config['owner_table'], $alias);
						
		$this->mapper->add_conditions($query, $conditions);
		return $query;
	}
	
	protected function process_items_relationship($config, $query, $group, $relationship, $plan) {
		$subquery = $this->get_owner_ids_subquery($params, $group->conditions(), $alias);
		$this->id_strategy->add_condition($query, $group->logic, $group->negated(), $config['owner_id_field'], $subquery);
	}
	
	protected function process_owner_relationship($config, $query, $current_alias, $conditions, $relationship, $plan) {
		$subquery = $this->get_item_ids_subquery($params, $group->conditions(), $alias);
		$this->id_strategy->add_condition($$query, $group->logic, $group->negated(), $config['owner_id_field'], $subquery);
	}
	
	public function process_relationship($query, $model_name, $relationship, $plan) {
		
	}
}