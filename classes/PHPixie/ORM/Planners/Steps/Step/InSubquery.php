<?php

namespace PHPixie\ORM\Query\Plan\Step;

class InSubquery{
	
	protected $query;
	protected $placeholder;
	protected $field;
	
	public function __construct($query, $placeholder, $field) {
		if (count($query->get_fields()) !== 1)
			throw new \PHPixie\ORM\Exception\Mapper("A field subquery must return only a single column.");
		
		$this->query = $query;
		$this->placeholder = $placeholder;
		$this->field = $field;
		
	}
	
	public function execute() {
		$ids = $this->query
				->execute()
				->get_column();
		
		$this->placeholder
						->where($field, 'in', $ids);
	}
}