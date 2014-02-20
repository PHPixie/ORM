<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Model;

class Owner extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Model {
	
	public function load() {
		return $this->query()->find();
	}
	
	public function set($owner) {
		$plan = $this->handler->link_plan($this->side->config(), $owner, $this->model);
		$plan->execute();
	}
	
	public function unlink() {
		$plan = $this->handler->unlink_item_plan($this->side->config(), $this->model);
		$plan->execute();
	}
	
}