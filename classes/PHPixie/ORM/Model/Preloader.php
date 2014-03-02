<?php

namespace PHPixie\ORM\Model;

abstract class Preloader{
	
	protected $link;
	protected $loader;
	protected $reusable_result_step;
	protected $items;
	
	public function __construct($link, $loader, $reusable_result_step) {
		$this->link = $link;
		$this->loader = $loader;
		$this->reusable_result_step = $reusable_result_step;
	}
	
	public function property_name() {
		return $this->property_name;
	}
	
	public function get_model($id) {
		$data = $this->items[$id];
		if($data instanceof \PHPixie\ORM\Model)
			return $data;
		
		$model = $this->loader->load($data);
		$this->items[$id] = $model;
		return $model;
	}
	
	abstract public function load_for($owner);
	
	
}