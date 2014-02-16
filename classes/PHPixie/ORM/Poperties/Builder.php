<?php

namespace PHPixie\ORM\Properties;

class Builder {
	protected $orm;
	protected $relationship_map;
	
	public function __construct($orm, $relatonship_map) {
		$this->relationship_map = $relationship_map;
	}
	
	public function model_property($model, $property_name) {
		$side = $this->relationship_map->get_side($model->model_name(), $property_name);
		return $this->orm->model_property($side, $model);
	}
	
	public function query_property($model, $name) {
		$side = $this->relationship_map->get_side($model->model_name(), $property_name);
		return $this->orm->query_property($side, $model);
	}
}