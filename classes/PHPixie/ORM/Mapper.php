<?php

namespace PHPixie\ORM;

class Mapper {
	protected $orm;
	protected $group_mapper;
	
	public function __construct($orm, $group_mapper, $repository_registry) {
		$this->orm = $orm;
		$this->group_mapper = $group_mapper;
	}
	
	public function map_delete($query) {
		$plan = $this->orm->plan();
		$model_name = $query->model_name();
		$repository = $this->repository_registry->get($model_name);

		$db_query = $repository->query('delete');
		$this->group_mapper->map_conditions($db_query, $query->conditions(), $model_name, $plan);
		$plan->push($this->steps->query($db_query))
		return $plan;
	}

	public function map_update($query, $data) {
		$plan = $this->orm->plan();
		$model_name = $query->model_name();
		$repository = $this->repository_registry->get($model_name);

		$db_query = $repository->query('update');
		$db->query->data($data);
		$this->group_mapper->map_conditions($db_query, $query->conditions(), $model_name, $plan);
		$plan->push($this->steps->query($db_query));
		return $plan;
	}

	public function map_find($query, $preload) {
		$model_name = $query->model_name();
		$result_plan = $this->orm->result_plan($model_name);
		$repository = $this->repository_registry->get($model_name);

		$db_query = $repository->query('select');
		$this->group_mapper->map_conditions($db_query, $query->conditions(), $model_name, $result_plan->required_plan());
		$result_step = $this->steps->reusable_result($db_query);
		$plan->set_result_step($result_step);
		
		foreach($preload as $relationship)
			$this->add_preloaders($relationship, $model, $plan->loader(), $plan->preload_plan());
		
		return $plan;
	}
	
	protected function add_preloaders($relationship, $model, $loader, $plan) {
		$path = explode('.', $relationship);
		foreach($path as $rel) {
			$preloader = $loader->get_preloader($relationship);
			if($preloader === null) {
				$preloader = $this->build_preloader($model, $relationship, $loader->result_step(), $plan);
				$loader->set_preloader($relationship, $loader);
			}
			$model = $preloader->model_name();
			$loader = $preloader;
		}
	}
	
	protected function build_preloader($model, $relationship, $result_step, $plan) {
		$link = $this->relationship_registry->get_link($current_model, $relationship);
		$handler = $this->orm->handler($link->relationship_type());
		return $handler->preloader($link, $result_step, $preload_plan);
	}
}