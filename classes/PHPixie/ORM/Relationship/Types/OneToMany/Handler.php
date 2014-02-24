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
		$owner_repository = $this->registry_repository->get($config->owner_model);

		$owner_collection = $this->orm->collection($config->owner_model);
		$owner_collection->add($owner);

		$plan = $this->orm->plan();
		$query = $items_repository->query()->in($items);
		$this->planners->update()->subquery($query, $config->item_key, $owner_collection, $owner_repository->id_field(), $plan);
		return $plan;
	}
	
	public function unlink_item_plan($config, $items) {
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

	public function map_relationship($link, $group, $query, $plan) {
		$config = $link->config();
		$item_repository = $this->registry_repository->get($config->item_model);
		$owner_repository = $this->registry_repository->get($config->owner_model);
		$conditions = $group->conditions();

		if($link->type() == 'item') {
			$subquery_repository = $item_repository;
			$query_field = $owner_repository->id_field();
			$subquery_field = $config->item_key;
		}else{
			$subquery_repository = $owner_repository;
			$query_field = $config->item_key;
			$subquery_field = $owner_repository->id_field();
		}

		$subquery = $subquery_repository->query();
		$this->group_mapper->map_conditions($subquery, $conditions, $subquery_repository->model_name(), $plan);
		$this->planners->in_subquery(
										$query,
										$query_field,
										$subquery,
										$subquery_field,
										$plan,
										$group->logic,
										$group->negated()
									);
	}
	
	
}