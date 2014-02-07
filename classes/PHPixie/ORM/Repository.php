<?php

namespace PHPixie\ORM;

class  Repository {
	
	protected $model_name;
	protected $plural_name;
	protected $connection_name;
	
	public function __construct($db, $model_name, $plural_name, $config) {
		$this->db = $db;
		$this->model_name = $model_name;
		$this->plural_name = $plural_name;
		$this->connection_name = $config->get('connection', 'default');
	}
	
	public function connection() {
		return $this->db->get($this->connection_name);
	}
}