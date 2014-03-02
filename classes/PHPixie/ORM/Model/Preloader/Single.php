<?php

namespace PHPixie\ORM\Model\Preloader;

abstract class Single extends \PHPixie\ORM\Model\Preloader {
	
	protected $map;
	
	public function load_for($owner) {
		if ($this->items === null)
			$this->process_items();
		
		$id = $this->get_item_id($owner);
		return $this->get_model($id);
	}
	
	protected function process_items();
	
}