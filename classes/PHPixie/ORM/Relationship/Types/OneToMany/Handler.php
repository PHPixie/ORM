<?php

namespace PHPixe\ORM\Relationships\OneToMany;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler {
	
	public function query($side, $related) {
		$config = $side->config();
		if($side->type() == 'items')
			return $this->build_query($config->item_model, $related->owner_property, $related);
		
		return $this->build_query($config->owner_model, $related->items_property, $related);
	}
	
	public function link_plan($config, $owner, $items) {
		$items_repository = $this->registry_repository->get($config->item_model);
		$query = $items_repository->query()->in($items);
		return $this->get_update_plan($config, $query, $owner->id_field());
	}
	
	public function unlink_item_plan($config, $item) {
		$items_repository = $this->registry_repository->get($config->item_model);
		$query = $items_repository->query()->in($item);
		return $this->get_update_plan($config, $query, null);
	}
	
	public function unlink_owner_plan($config, $owner) {
		$items_repository = $this->registry_repository->get($config->item_model);
		$query = $items_repository->query()
										->related($config->item_property, $owner);
		return $this->get_update_plan($config, $query, null);
	}
	
	protected function get_update_plan($config, $query, $owner_id) {
		return $query->update_plan(array(
									$config->item_key => $owner_id
								));
	}
	
	/*
	protected $side_handlers = array();
	
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
	*/
}