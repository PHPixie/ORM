<?php

namespace PHPixie\ORM

class Collection extends Query{
	protected $query;
	protected $ids = array();
	protected $items = array();
	
	public function query() {
		return clone $this->query;
	}
}