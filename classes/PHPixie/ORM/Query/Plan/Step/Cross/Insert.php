<?php

namespace PHPixie\ORM\Query\Plan\Step\Cross;

class Insert extends \PHPixie\ORM\Query\Plan\Step\Cross {
	
	public function execute() {
		$values = array();
		
		foreach($this->get_ids('left') as $left_id)
			foreach($this->get_ids('right') as $right_id)
				$values[] = array($left_id, $right_id);
				
		$this->query
			->batch_insert($this->keys, $values)
			->execute();
	}
}