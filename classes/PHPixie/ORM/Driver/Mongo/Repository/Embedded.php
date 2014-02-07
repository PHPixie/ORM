<?php

namespace PHPixie\ORM\Driver\Mongo\Repository;

class Embedded extends \PHPixie\ORM\Repository {
	
	protected $collection;
	protected $id_field = '_id';
	protected $path;
	
	public function __construct($db, $model_name, $plural_name, $config) {
		parent::__construct($model_name, $plural_name, $config);
		$this->collection = $config->get('collection', $plural_name)
	}
	
	public function db_query($type) {
		return $this->orm->embedded_query($type, $connection_name);
	}
	
	public function id_field() {
		return $this->id_field;
	}
}