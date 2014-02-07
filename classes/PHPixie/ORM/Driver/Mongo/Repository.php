<?php

namespace PHPixie\ORM\Driver\Mongo;

class Repository extends \PHPixie\ORM\Repository {
	
	protected $collection;
	protected $id_field;
	
	public function __construct($db, $model_name, $plural_name, $config) {
		parent::__construct($model_name, $plural_name, $config);
		$this->collection = $config->get('collection', $plural_name);
		$this->id_field  = $config->get('id_field', '_id');
	}
	
	public function db_query($type) {
		return $this->connection()
					->query($type)
					->collection($this->collection);
	}
	
	public function id_field() {
		return $this->id_field;
	}
}