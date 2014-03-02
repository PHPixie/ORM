<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Model {
	
	public function load() {
		return $this->query()->find_all();
	}
	
	public function add($items, $reset = true) {
		$plan = $this->handler->link_plan($this->link, $this->model, $items);
		$plan->execute();
		if($reset)
			$this->reset();
	}
	
	public function remove($items, $reset = true) {
		$plan = $this->handler->unlink_items_plan($this->link, $items, $this->model);
		$plan->execute();
		if($reset)
			$this->reset();
	}
	
}