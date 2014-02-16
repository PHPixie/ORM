<?php

namespace PHPixie\ORM;

class  Model {
	protected $property_builder;
	
	public function __construct($property_builder) {
		$this->property_builder = $property_builder;
	}
	
	public function as_array() {
		return $this->repository->model_data($this);
	}
	
	public function save() {
		$this->repository->save($this);
		return $this;
	}
	
	public function __get($name) {
		$property = $this->property_builder->model_property($this, $name);
		if ($property !== null)
			return $this->$name = $property;
	}
}
