<?php

namespace PHPixie\ORM\Conditions;

class Builder extends \PHPixie\DB\Conditions\Builder {
	
	protected $conditions;
	
	public function add_operator_condition($logic, $negate, $field, $operator, $values) {
		if (($pos = strpos($field, '>')) !== false || ($pos = strpos($field, '.', -1)) !== false) {
			$relationship = substr($field, 0, $pos);
			$field = substr($field, $pos + 1);
		}else
			$relationship = null;
		
		$condition = $this->conditions->operator($field, $operator, $values);
		$this->add_to_relationship($logic, $negate, $condition, $relationship);
	}
	
	public function start_relationship_group($relationship, $logic, $negate) {
		foreach(explode('.', $relationship) as $key => $rel) {
			$group = $this->relationship_group($rel);
			if ($key > 0) {
				$root = $group;
				$this->add_subgroup('and', false, $group, $current);
			}
			$current = $group;
		}
		
		$this->push_group($logic, $negate, $root);
		return $this;
	}
	
	
	public function add_related($logic, $negate, $relationship, $condition) {
		if (!is_callable($condition)) 
			return $this->add_collection($logic, $negate, $condition, $relationship);
		
		$this->start_relationship_group($logic, $negate, $relationship);
		call_user_func($condition, $this);
		$this->end_group();
	}
	
	protected function add_collection($logic, $negate, $collection_items, $relationship = null) {
		$condition = $this->conditions->collection($collection_items);
		$this->add_to_relationship($collection, $relationship);
	}
	
	protected function add_to_relationship($logic, $negate, $condition, $relationship) {
		if ($relationship !== null) 
			$this->start_relationship_group($logic, $relationship);
		
		$this->add_to_current($logic, $negate, $condition);
		
		if ($relationship !== null) 
			$this->end_group();
	}
	
	
	public function where() {
		return $this->add_condition('and', false, func_get_args());
	}
	
	public function or_where() {
		return $this->add_condition('or', false, func_get_args());
	}
	
	public function xor_where() {
		return $this->add_condition('xor', false, func_get_args());
	}
	
	public function and_where_not() {
		return $this->add_condition('and', true, func_get_args());
	}
	
	public function or_where_not() {
		return $this->add_condition('or', true, func_get_args());
	}
	
	public function xor_where_not() {
		return $this->add_condition('xor', true, func_get_args());
	}
	
	public function in($collection_items) {
		return $this->add_collection('and', false, $collection_items);
	}
	
	public function or_in($collection_items) {
		return $this->add_collection('or', false, $collection_items);
	}
	
	public function xor_in($collection_items) {
		return $this->add_collection('xor', false, $collection_items);
	}
	
	public function and_in_not($collection_items) {
		return $this->add_collection('and', true, $collection_items);
	}
	
	public function or_in_not($collection_items) {
		return $this->add_collection('or', true, $collection_items);
	}
	
	public function xor_in_not($collection_items) {
		return $this->add_collection('xor', true, $collection_items);
	}
	
	
	public function related($relationship, $condition) {
		return $this->add_related('and', false, $relationship, $condition);
	}
	
	public function or_related($relationship, $condition) {
		return $this->add_related('or', false, $relationship, $condition);
	}
	
	public function xor_related($relationship, $condition) {
		return $this->add_related('xor', false, $relationship, $condition);
	}
	
	public function and_related_not($relationship, $condition) {
		return $this->add_related('and', true, $relationship, $condition);
	}
	
	public function or_related_not($relationship, $condition) {
		return $this->add_related('or', true, $relationship, $condition);
	}
	
	public function xor_related_not($relationship, $condition) {
		return $this->add_related('xor', true, $relationship, $condition);
	}
}