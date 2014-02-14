<?php

namespace PHPixie\ORM\Relationship\OneToMany\Properties\Property;

class Owner {
	
	protected $model;
	protected $name;
	protected $handler;
	protected $owner;
	protected $loaded = false;
	
	public function __construct($model, $name, $handler) {
		$this->model = $model;
		$this->name = $name;
		$this->handler = $handler;
	}
	
	public function __invoke() {
		if (!$this->loaded)
			$this->load();
			
		return $this->owner;
	}
	
	public function load() {
		$this->owner = $this->query()->find();
		$this->loaded = true;
	}
	
	public function query() {
		$this->handler->owner_query($this->model, $this->name);
	}
}