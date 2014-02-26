<?php

namespace PHPixie\ORM\Relationships\OneToMany\Preloader;

class Items implements \PHPixie\ORM\Relationships\Type\Preloader{
	
	protected $link;
	protected $reusable_result_step;
	protected $repository;
	protected $items;
	
	public function __construct($link, $reusable_result_step, $repository) {
		$this->link = $link;
		$this->reusable_result_step = $reusable_result_step;
		$this->repository = $repository;
	}
	
	protected function load_for($owner) {
		if ($this->items_map === null)
			$this->map_items();
		
	}
	
	protected function map_items() {
		$this->items = array();
		$this->items_map = array();
		$id_field = $this->repository->id_field();
		$key = $this->link->config()->item_key;
		foreach($this->reusable_result_step->iterator() as $item_data) {
			$id = $item_data->$id_field;
			$this->items[$id] = $item_data;
			$owner_id = $item_data->$key;
			if (!isset($this->items_map[$owner_id]))
				$this->items_map[$owner_id] = array();
			$this->items_map[$owner_id][] = $id;
		}
	}
	
	public function get($id) {
		$data = $this->items[$id];
		if($data instanceof \PHPixie\ORM\Model)
			return $data;
		
		$model = $this->repository->load($data);
		$this->items[$id] = $model;
		return $model;
	}
}