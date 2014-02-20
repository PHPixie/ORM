<?php

namespace PHPixie\ORM\Relationship\Types\OneToMany\Property\Query;

class Owner extends \PHPixie\ORM\Relationship\Types\OneToMany\Property\Query {
	
	public function set($owner) {
		$plan = $this->handler->link_plan($this->side->config(), $owner, $this->query);
		$plan->execute();
	}
	
	public function unlink() {
		$plan = $this->handler->unlink_item_plan($this->side->config(), $this->query);
		$plan->execute();
	}
	
}