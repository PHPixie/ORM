<?php

namespace PHPixe\ORM\Relationships\OneToMany;

class Handler{
	
	protected $side_handlers = array();
	
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
	
	public function map_relationship_group($group, $model_name, $query, $plan) {
		$config = $this->get_config($model_name, $group->relationship);
		$side = $this->get_side($config, $model_name, $group);
		$this->get_side($side, $config)->map_condition_group($config, $query, $relationship, $plan);
	}
	
	public function map_model_relationship($relationship, $model_name, $query, $plan) {
		$config = $this->get_config($model_name, $relationship->relationship);
		$side = $this->get_side($config, $model_name, $relationship);
		$this->get_side($side, $config)->map_model_condition($config, $query, $relationship, $plan);
	}
	
	protected function get_side($side, $config) {
		if (!isset($this->side_handlers[$side]))
			$this->side_handlers[$side] = array();
		
		$driver_name = $config["{$side}_repo"]->connection()->driver_name();
		
		if (!isset($this->side_handlers[$side][$driver_name]))
			$this->side_handlers[$side][$driver_name] = $this->one_to_many->side_handler($side, $driver_name);
		
		return $this->side_handlers[$side][$driver_name];
	}
}