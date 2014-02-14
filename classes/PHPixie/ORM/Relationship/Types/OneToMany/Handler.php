<?php

namespace PHPixe\ORM\Relationships\OneToMany;

class Handler{
	
	protected $side_handlers = array();
	
	public function item_query($owner_side, $owner_query) {
		$config = $owner_side->config();
		$this->orm->repository($config->item_model)->query()
														->related($config->item_property, $owner_query);
	}
	
	public function owner_query($item_side, $item_query) {
		$config = $item_side->config();
		$this->orm->repository($config->owner_model)->query()
														->related($config->owner_property, $item_query);
	}
	
	public function map_condition_group($group, $model_name, $query, $plan) {
		$side = $this->relationship_map->get($model_name, $group->relationship);
		
		$this->side_handler($side)
				->map_conditions($group->conditions(), $query, $side->config(), $plan);
	}
	
	public function map_model_condition($model, $model_name, $query, $plan) {
		$side = $this->relationship_map->get($model_name, $group->relationship);
		
		$this->side_handler($side)
				->map_model_condition($group->conditions(), $query, $side->config(), $plan);
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