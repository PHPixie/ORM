<?php

namespace PHPixie\ORM\Model\Preloader;

abstract class Multiple extends \PHPixie\ORM\Model\Preloader {
	
	protected $map;
	
	public function load_for($owner) {
		if ($this->items === null)
			$this->process_items();
		
		$ids = $this->get_item_ids($owner);
		return $this->iterator($ids);
	}
	
	public function iterator($ids) {
		return Iterator\Iterator($this, $ids)
	}
	
	protected function get_item_ids($owner);
	protected function process_items();
}