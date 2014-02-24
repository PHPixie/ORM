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
		$result_plan = $this->orm->result_plan();
		$model_name = $query->model_name();
		$repository = $this->repository_registry->get($model_name);

		$db_query = $repository->query('select');
		$this->group_mapper->map_conditions($db_query, $query->conditions(), $model_name, $result_plan->required_plan());
		$result_step = $this->steps->result_step($db_query);
		$plan->set_result_step($result_step);
		foreach($preload as $relationship)
		$current_result_step = $result_step;
		return $plan;
	}
}