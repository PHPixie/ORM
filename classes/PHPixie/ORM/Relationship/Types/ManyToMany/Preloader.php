<?php

namespace PHPixie\ORM\Relationships\ManyToMany;

class Preloader{

	protected $repository;
	protected $result;
	protected $owner_field;
	protected $items_map;
	
	public function __construct($preloaders) {
		$this->repository = $repository;
		$this->result = $result;
		$this->owner_field = $owner_field;
	}
	
	public function set($owner, $property) {
		$property = $this->get_property();
		$loader = $this->orm->loader($item_model, $this->get_items($owner), $preloaders);
		$property->set_loader($loader);
	}
	
	protected function get_items($owner) {
		if ($this->items_map === null)
			$this->items_map = $this->build_items_map();
			
		return $this->items_map[$owner->id()];
	}
	
	protected function build_items_map() {
		$items = array();
		$id_field = $this->repository->id_field();
		foreach($this->data_result as $row)
			$items[$row->$id_field] = &$row;
		
		$items_map = array();
		$owner_key = $this->owner_pivot_key;
		$item_key = $this->pivot_item_key;
		
		foreach($this->pivot_result as $row) {
			if (!isset($items_map[$row->$owner_key]))
				$items_map[$row->owner_key] = array();
			$items_map[$row->owner_key][] = &$items[$row->$item_key];
		}
		
		return $items_map;
	}
}
