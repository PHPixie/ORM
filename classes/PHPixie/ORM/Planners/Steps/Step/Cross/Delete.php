<?php

namespace PHPixie\ORM\Query\Plan\Step\Cross;

class Delete extends \PHPixie\ORM\Query\Plan\Step\Cross {
	
	public function execute() {
		$this->query
					->where($this->keys['left'], 'in', $this->get_ids('left'))
					->where($this->keys['right'], 'in', $this->get_ids('right'));
		$this->query->execute();
	}
}