<?php

namespace PHPixie\ORM\Iterator;

class Result impelements \Iterator{
	
	public abstract function current();
	public abstract function key();
	public abstract function valid();
	public abstract function next();
	public abstract function rewind();
	
	public function get_field($field) {
		$this->rewind();
		$values = array();
		foreach($this as $row)
			$values[] = $row->$field;
		return $values;
	}
}