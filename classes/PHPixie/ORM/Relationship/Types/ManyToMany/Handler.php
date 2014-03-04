<?php

namespace PHPixe\ORM\Relationships\Types\ManyToMany;

class Handler extends \PHPixie\ORM\Relationship\Type\Handler {
	
	public function query($link, $related) {
		$config = $link->config();
		$side = $link->type();
		return $this->build_query($config->{"{$side}_model"}, $config->{"{$side}_property"}, $related);
	}
	
	
	public function link($link, $items, $opposing_items) {
		return $this->modify_link('link', $link, $items, $opposing_items);
	}
	
	public function unlink($link, $items, $opposing_items) {
		return $this->modify_link('unlink', $link, $items, $opposing_items);
	}
	
	public function unlink_all($link, $items) {
		$side = $link->type();
		$config = $link->config();
		
		$first_side = $this->get_planner_side($config, $side, $items);
		$pivot = $this->get_planner_pivot($config);
		
		$plan = $this->orm->plan();
		$this->planners->pivot()->unlink($pivot, $first_side, $plan);
		return $plan;
	}
	
	protected function modify_link($method, $link, $items, $opposing_items) {
		$side = $link->type();
		$config = $link->config();
		
		$first_side = $this->get_planner_side($config, $side, $items);
		$second_side = $this->get_planner_side($config, $this->opposing_side($side), $opposing_items);
		$pivot = $this->get_planner_pivot($config);
		
		$plan = $this->orm->plan();
		$pivot_planner = $this->planners->pivot();
		
		if($method === 'link') {
			$pivot_planner->link($pivot, $first_side, $second_side, $plan);
		}else {
			$pivot_planner->unlink($pivot, $first_side, $plan, $second_side);
		}
		
		return $plan;
	}
	
	protected function opposing_side($side) {
		if ($side === 'left')
			return 'right';
		
		if ($side === 'right')
			return 'left';
			
		throw new \PHPixie\ORM\Exception\Mapper("Side must be either 'left' or 'right', '{$side}' was passed.")
	}
	
	protected function get_planner_side($config, $side, $items) {
		$model = $config->get("{$side}_model");
		$collection = $this->orm->collection($model);
		$collection->add($items);
		$repository = $this->repository_registry->get($model);
		$pivot_key = $config->get("{$side}_pivot_key");
		return $this->planners->pivot()->side($collection, $repository, $pivot_key);
	}
	
	protected get_planner_pivot($config) {
		$pivot_connection = $this->db->get($config->pivot_connection);
		return $this->planners->pivot()->pivot($pivot_connection, $config->pivot)
	}
	
	protected function get_sides($config) {
		$sides = array();
		foreach(array('left', 'right') as $side) {
			$model = $config->get("{$side}_model");
			$repo = $this->registry_repository->get($model);
			$id_field = $repo->id_field();
			$pivot_key = $config->get("{$side}_pivot_key");
			
			$sides[$side] = array(
				'model' => $model,
				'repo' => $repo,
				'id_field' => $id_field,
				'pivot_key' => $pivot_key
			);
		}
		return $sides;
	}
	
	public function map_relationship($link, $group, $query, $plan) {
		$side = $link->type();
		$config = $link->config();
		$opposing = $this->opposing_side($side);
		$sides = $this->get_sides($config);
		$pivot_connection = $this->db->get($config->pivot_connection);
		$in_planner = $this->planners->in();
		
		$opposing_query = $sides[$opposing]['repo']->db_query()->fields(array($sides[$opposing]['id_field']));
		$pivot_query = $pivot_connection->query('select')->collection($config->pivot);
		
		$this->group_mapper->map_conditions($opposing_query, $group->conditions(), $sides[$opposing]['model'], $plan);
		
		$in_planner->query(
							$pivot_query,
							$sides[$opposing]['pivot_key'],
							$opposing_query,
							$sides[$opposing]['id_field'],
							$plan
						);
						
		$in_planner->query(
							$query,
							$sides[$side]['id_field'],
							$pivot_query,
							$sides[$side]['pivot_key'],
							$plan,
							$group->logic(),
							$group->negated()
						);
	}
	
	public function preload($link, $loader, $result_step, $result_plan) {
		$side = $link->type();
		$config = $link->config();
		$opposing = $this->opposing_side($side);
		$sides = $this->get_sides($config);
		$pivot_connection = $this->db->get($config->pivot_connection);
		$in_planner = $this->planners->in();
		$preload_plan = $result_plan->preload_plan();

		$pivot_query = $pivot_connection->query('select')->collection($config->pivot);
		$placeholder = $query->get_where_builder()->add_placeholder();
		$pivot_step = $this->steps->in($placeholder, $sides[$opposing]['pivot_key'], $result_step, $sides[$opposing]['id_field']);
		$preload_plan->push($pivot_step);
		
		$query = $sides[$side]['repo']->db_query();
		$in_planner->query(
					$query,
					$sides[$side]['id_field'],
					$pivot_query,
					$sides[$side]['pivot_key'],
					$plan
				);
		
		$preload_step = $this->steps->result($query);
		$preload_plan->push($preload_step);
		return $preload_step;
	}
}