<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Items extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Model {
	
	public function add($items, $reset = true) {
		$plan = $this->handler->link_plan($this->side->config(), $this->query, $items);
		$plan->execute();
		if($reset)
			$this->reset();
	}
	
	public function remove($items, $reset = true) {
		$plan = $this->handler->unlink_items_plan($this->side->config(), $items, $this->query);
		$plan->execute();
		if($reset)
			$this->reset();
	}
	
}