<?php

namespace PHPixie\ORM;

class Mapper {
	protected $orm;
	protected $group_mapper;
	
	public function __construct($orm, $group_mapper, $repository_registry) {
		$this->orm = $orm;
		$this->group_mapper = $group_mapper;
	}
	
	public function map($query) {
		$plan = $this->orm->plan();
		$model_name = $query->model_name();
		$repository = $this->repository_registry->get($model_name);
		$db_query = $repository->$query();
		$this->group_mapper->map_conditions($db_query, $query->conditions(), $model_name, $plan);
		$db
	}
}