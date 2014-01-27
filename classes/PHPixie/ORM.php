<?php

namespace PHPixie;

class ORM {
	
	public function operator($field, $operator, $values) {
		return new \PHPixie\ORM\Conditions\Condition\Operator($field, $operator, $values);
	}
	
	public function condition_group() {
		return new \PHPixie\ORM\Conditions\Condition\Group;
	}
	
	public function relationship_group($relationship) {
		return new \PHPixie\ORM\Conditions\Condition\Group\Relationship($relationship);
	}
}
