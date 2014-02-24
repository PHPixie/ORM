<?php

namespace PHPixie\ORM\Properties\Property;

abstract class Model {
	
	protected $model;
	
	protected $loaded = false;
	protected $value;
	
	public function __construct($handler, $side, $model) {
		parent::__construct($handler, $side);
		$this->model = $model;
	}
	
	public function __invoke() {
		if ($this->loaded)
			$this->reload();
		return $this->value;
	}
	
	public function reload() {
		$this->laded = true;
		$this->loaded = $this->load();
		return $this->loaded;
	}
	
	public function reset() {
		$this->value = null;
		$this->loaded = false;
	}
	
	protected function property_owner() {
		return $this->model;
	}
	
	abstract protected function load();
}