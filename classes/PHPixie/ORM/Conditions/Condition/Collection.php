<?php

namespace PHPixie\ORM\Conditions\Condition;

class Collection extends \PHPixie\ORM\Conditions\Condition {
	
	public $collection_items;
	
	public function __construct($collection_items) {
		$this->collection_items = $collection_items;
	}
}