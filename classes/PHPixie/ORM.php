<?php

namespace PHPixie;

class ORM {
	
	protected $db;
	protected $config;
	protected $relationship_map;
	protected $property_builder;
	protected $repository_registry;
	protected $relationship_types = array();
	protected $drivers = array();
	protected $mapper;
	protected $group_mapper;
	
	public function __construct($db, $config) {
		$this->db = $db;
		$this->config = $config;
	}
	
	public function driver($name) {
		if (!isset($this->drivers[$name]))
			$this->drivers[$name] = $this->build_driver($name);
	
		return $this->drivers[$name];
	}

	public function build_driver($name) {
		$class = '\PHPixie\ORM\Driver\\'.$name;
		return new $class($this);
	}

	public function relationship_type($name) {
		if (!isset($this->relationship_types[$name]))
			$this->relationship_types[$name] = $this->build_relationship_type($name);
	
		return $this->relationship_types[$name];
	}

	public function build_relationship_type($name) {
		$class = '\PHPixie\ORM\Relationship\Types\\'.$name;
		return new $class($this);
	}
	
	public function repository($model_name, $model_config) {
		$connection_name = $model_config->get('connection');
		$driver_name = $this->db->driver_name($connection_name);
		$driver = $this->driver($driver_name);
		return $driver->repository($model_name, $model_config);
	}
	
	public function relationship_map() {
		if($this->relationship_map === null)
			$this->relationship_map = $this->build_relationship_map();
		return $this->relationship_map;
	}
	
	protected function build_relationship_map() {
		$relationship_config = $this->config->slice('relationships');
		return new \PHPixie\ORM\Relationship\Map($this, $relationship_config);
	}
	
	public function repository_registry() {
		if($this->repository_registry === null)
			$this->repository_registry = $this->build_repository_registry();
		return $this->repository_registry;
	}
	
	protected function build_repository_registry() {
		$model_config = $this->config->slice('models');
		return new \PHPixie\ORM\Relationship\Registry($this, $model_config);
	}
	
	public function operator($field, $operator, $values) {
		return new \PHPixie\ORM\Conditions\Condition\Operator($field, $operator, $values);
	}
	
	public function condition_group() {
		return new \PHPixie\ORM\Conditions\Condition\Group;
	}
	
	public function relationship_group($relationship) {
		return new \PHPixie\ORM\Conditions\Condition\Group\Relationship($relationship);
	}
	
	public function property_builder() {
		if ($this->property_builder === null)
			$this->property_builder = $this->build_property_builder();
		return $this->property_builder;
	}
	
	public function build_property_builder() {
		return new \PHPixie\ORM\Properties\Builder($this, $this->relationship_map());
	}
	
	public function model_property($side, $model) {
		$relationship = $this->relationship_type($side->relationship_type());
		return $relationship->model_property($side);
	}
	
	public function query_property($side, $model) {
		$relationship = $this->relationship($side->relationship_type());
		return $relationship->query_property($side);
	}
	
	public function group_mapper() {
		if($this->group_mapper === null)
			$this->group_mapper = $this->build_group_mapper();
		return $this->group_mapper;
	}
	
	public function build_group_mapper() {
		return \PHPixie\ORM\Mapper\Group($this);
	}
	
	public function mapper() {
		if($this->mapper === null)
			$this->mapper = $this->build_mapper();
		return $this->mapper;
	}
	
	public function build_mapper() {
		return \PHPixie\ORM\Mapper($this, $this->group_mapper());
	}
}
