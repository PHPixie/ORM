<?php

namespace PHPixie\ORM;

class  Model {
	protected $repository;
	
	public function as_array() {
		return $this->repository->model_data($this);
	}
	
	public function save() {
		$this->repository->save($this);
		return $this;
	}
	
	public function __get($name) {
		$property = $this->mapper->get_property($this->model_name, $property);
		if ($property !== null)
			return $this->$name = $property;
	}
}
