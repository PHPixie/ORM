<?php

namespace PHPixie\ORM\Relationship\OneToMany\Properties\Property;

class Items {
	
	protected $model;
	protected $name;
	protected $handler;
	
	
	public function __construct($model, $name, $handler) {
		$this->model = $model;
		$this->name = $name;
		$this->handler = $handler;
	}
	
	public function add($model, $items) {
		$this->handler->add_items($this->model, $this->name, $items);
	}
	
	public function remove($model, $items) {
		$this->handler->remove_items($this->model, $this->name, $items);
	}
	
	public function query() {
		$this->handler->items_query($this->model, $this->name);
	}
}