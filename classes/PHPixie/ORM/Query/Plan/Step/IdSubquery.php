<?php

namespace PHPixie\ORM\Query\Plan\Step;

class IdSubquery{
	
	protected $query;
	protected $in_condition;
	protected $id_field;
	
	public function __construct($query, $id_field, $in_condition) {
		$this->query = $query;
		$this->id_field = $id_field;
		$this->in_condition = $in_condition;
	}
	
	public function execute() {
		$ids = $this->query
				->fields(array($this->id_field))
				->execute()
				->get_column($this->id_field);
				
		$this->in_condition->values = array($ids);
	}
}