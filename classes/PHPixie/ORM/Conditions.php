<?php

namespace PHPixie\ORM;

class Conditions {
	
	public function placeholder() {
		return new \PHPixie\ORM\Conditions\Condition\Placeholder();
	}
	
	public function operator($field, $operator, $values) {
		return new \PHPixie\ORM\Conditions\Condition\Placeholder($field, $operator, $values);
	}
	
	public function group() {
		return new \PHPixie\ORM\Conditions\Condition\Group();
	}
	
	public function relationship_group($relationship) {
		return $this->orm->relationship_group($relationship);
	}

	public function collection($collection_items) {
		return new \PHPixie\ORM\Conditions\Condition\Collection($collection_items);
	}
	
}