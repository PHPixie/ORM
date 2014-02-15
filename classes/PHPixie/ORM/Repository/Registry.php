<?php

namespace PHPixie\ORM\Relationship;

class Registry {
	
	protected $repositories = array();
	
	public function __construct($orm, $config) {
		foreach(array_keys($config->data()) as $model_name) {
			$model_config = $this->config->slice($model_name);
			$repository = $orm->build_repository($model_name, $model_config);
			$this->repositories[$model_name] = $repository;
		}
	}
	
	public function get($model_name) {
		return $this->repositories[$model_name];
	}
}