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
		$plan = $this->orm->plan();
		$item_collection = $this->orm->collection($config->item_model);
		$item_collection->add($items);
		$items_repository = $this->registry_repository->get($config->item_model);
		$item_id_field = $items_repository->id_field();
		$update_query = $items_repository
							->db_query('update')
							->data(array(
								$config->item_key => $owner->id
							));
		$this->planners->in_condition($update_query, $item_id_field, $collection, $item_id_field, $plan);
		$plan->push($this->steps->query($update_query));
		return $plan;
	}
	
	public function unlink_item_plan($config, $item) {
		$plan = $this->orm->plan();
		$items_repository = $this->registry_repository->get($config->item_model);
		$item_id_field = $items_repository->id_field();
		
		$update_query = $items_repository
							->db_query('update')
							->data(array(
								$config->item_key => null
							));
		$update_query->where($item_id_field, $item->id());
		$plan->push($this->steps->query($update_query));
		return $plan;
	}
	
	public function unlink_owner_plan($config, $items, $required_owner = null) {
		$plan = $this->orm->plan();
		$item_collection = $this->orm->collection($config->item_model);
		$item_collection->add($items);
		$items_repository = $this->registry_repository->get($config->item_model);
		$item_id_field = $items_repository->id_field();
		$update_query = $items_repository
							->db_query('update')
							->data(array(
								$config->item_key => null
							));
		$this->planners->in_condition($update_query, $item_id_field, $collection, $item_id_field, $plan);
		$plan->push($this->steps->query($update_query));
		return $plan;
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