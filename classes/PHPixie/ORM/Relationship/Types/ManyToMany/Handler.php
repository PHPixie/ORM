<?php

namespace PHPixe\ORM\Relationships\Types\ManyToMany;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler {
	
	public function query($link, $related) {
		$config = $link->config();
		$side = $link->type();
		return $this->build_query($config->{"{$side}_model"}, $config->{"{$side}_property"}, $related);
	}
	
	public function link_plan($link, $side_items, $opposing_tems) {
		$side = $link->side();
		$opposing_side = $this->opposing_side($side);
		$side_model = $config->get("{$side}_model");
		$opposing_model = $config->get("{$opposing_side}_model");

		
		$side_repository = $this->registry_repository->get($side_model);
		$opposing_repository = $this->registry_repository->get($opposing_model);

		$side_collection = $this->orm->collection($side_model);
		$side_collection->add($owner);
		
		$side_collection = $this->orm->collection($side_model);
		$side_collection->add($owner);

		$plan = $this->orm->plan();
		$query = $items_repository->query()->in($items);
		$update_planner = $this->planners->update();
		$owner_field = $update_planner->field($owner, $owner_repository->id_field());
		$update_planner->plan(
								$query, 
								array($config->item_key => $owner_field), 
								$plan
							);
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

		if($link->type() === 'item') {
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
	
	public function preload($link, $loader, $result_step, $result_plan) {
		$config = $link->config();
		if($link->type() === 'item') {
			$query_repository = $item_repository;
			$query_field = $config->item_key;
			$result_field = $owner_repository->id_field();
		}else{
			$query_repository = $owner_repository;
			$query_field = $owner_repository->id_field();
			$result_field = $config->item_key;
		}
		
		$query = $preload_repository->db_query();
		$placeholder = $query->get_where_builder()->add_placeholder();
		$preload_step = $this->steps->in($placeholder, $query_field, $result_step, $result_field);
		$result_plan->preload_plan()->push($preload_step);
		return $preload_step;
	}
	
	protected opposing_side($side) {
		if($side === 'left'){
			return 'right';
		
		}elseif($side === 'right') {
			return 'left';
			
		}else
			throw new \Exception();
	}
}