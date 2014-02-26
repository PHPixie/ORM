<?php

namespace PHPixie\ORM\Query\Plan\Step\Query\Result;

class Single extends \PHPixie\ORM\Query\Plan\Step\Query\Result {
	
	protected $iterator = null;
	
	public function iterator() {
		if ($this->iterator === null)
			$this->iterator = $this->orm->result_iterator_iterator($this->data());
		
		return $this->iterator;
	}
}