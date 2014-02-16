<?php

namespace PHPixie\ORM\Relationship;

abstract class Side {
	
	protected $relationship;
	protected $config;
	protected $type;
	
	public function __construct($relationship, $type, $config) {
		$this->relationship = $relationship;
		$this->type = $type;
		$this->config = $config;
	}
	
	public function type() {
		return $this->type;
	}
	
	public function config() {
		return $this->config;
	}
	
	public abstract function model_name();
	public abstract function property_name();
	public abstract function relationship_type();
}