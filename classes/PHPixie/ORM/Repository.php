<?php

namespace PHPixie\ORM;

class  Repository {
	
	protected $model_name;
	protected $plural_name;
	protected $connection_name;
	
	public function __construct($db, $driver, $model_name, $plural_name, $config) {
		$this->db = $db;
		$this->driver = $driver;
		$this->model_name = $model_name;
		$this->plural_name = $plural_name;
		$this->connection_name = $config->get('connection', 'default');
	}
	
	public function connection() {
		return $this->db->get($this->connection_name);
	}
	
	public function load($data, $preloaders = array()) {
		$model = $this->driver->model($model_name, $data);
		foreach($preloaders as $property => $preloader)
			$model->$property->set_value($preloader->load_for($model));
		return $model;
	}
}