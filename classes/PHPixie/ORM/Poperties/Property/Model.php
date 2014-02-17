<?php

namespace PHPixie\ORM\Properties\Property;

class Model {
	
	protected $handler;
	protected $side;
	protected $model;
	
	protected $loaded = false;
	protected $value;
	
	public function __construct($handler, $side, $model) {
		$this->handler = $handler;
		$this->side = $side;
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
	
	public function query() {
		return $this->handler->query($this->side, $this->model);
	}
	
	abstract protected function load();
}