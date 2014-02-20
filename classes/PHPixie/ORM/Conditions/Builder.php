<?php

namespace PHPixie\ORM\Conditions;

class Builder extends \PHPixie\DB\Conditions\Builder {
	
	protected $orm;
	
	public function __construct($orm, $default_operator = '=') {
		$this->orm = $orm;
		$this->default_operator = $default_operator;
		$this->push_group($this->db->condition_group());
	}
	
	protected function operator($field, $operator, $values) {
		return $this->orm->operator($field, $operator, $values);
	}
	
	protected function condition_group() {
		return $this->orm->condition_group();
	}
	
	public function add_operator_condition($logic, $negate, $field, $operator, $values) {
		if (($pos = strpos($field, '>')) !== false || ($pos = strpos($field, '.', -1)) !== false) {
			$relationship = substr($field, 0, $pos);
			$field = substr($field, $pos + 1);
		}else
			$relationship = null;
		
		$condition = $this->build_operator_condition($negate, $field, $operator, $values);
		$this->add_condition_to_relationship($logic, $condition, $relationship);
	}
	
	public function add_relationship_condition($logic, $negate, $relationship, $value) {
		if (($pos = strpos($field, '.', -1)) !== false) {
			$parent_relationship = substr($field, 0, $pos);
			$field = substr($field, $pos + 1);
		}else
			$parent_relationship = null;
			
		$condition = $this->build_relationship_condition($negate, $parent_relationship, $value);
		$this->add_condition_to_relationship($logic, $condition, $parent_relationship);
	}
	
	protected function add_condition_to_relationship($logic, $condition, $relationship) {
		if($relationship === null) {
			$this->current_group->add($condition);
		}else {
			$this->start_relationship_group($logic, $relationship);
			$this->current_group->add($condition);
			$this->end_group();
		}
	}
	
	protected function relationship_group($relationship) {
		return $this->orm->relationship_group($relationship);
	}
	
	public function start_relationship_group($logic, $negate, $relationship) {
		foreach(explode('.', $relationship) as $key => $rel) {
			$group = $this->relationship_group($rel);
			if ($key > 0) {
				$root = $group;
				$this->add_subgroup('and', $group, $current);
			}
			$current = $group;
		}
		
		$this->push_group($logic, $negate, $root);
		return $this;
	}
	
	public function add_relationship($logic, $negate, $relationship, $condition) {
		if(is_callable($condition)){
			$this->start_relationship_group($logic, $negate, $relationship);
			call_user_func($condition, $this);
			$this->end_group();
		}else {
			$this->add_relationship_condition($logic, $negate, $relationship, $condition);
		}
		return $this;
	}
	
	protected function build_placeholder() {
		//NEEDS TEST
		return $this->orm->condition_placeholder();
	}
	
	public function related($relationship, $condition) {
		return $this->add_relationship('and', false, $relationship, $condition);
	}
	
	public function or_related($relationship, $condition) {
		return $this->add_relationship('or', false, $relationship, $condition);
	}
	
	public function xor_related($relationship, $condition) {
		return $this->add_relationship('xor', false, $relationship, $condition);
	}
	
	public function and_related_not($relationship, $condition) {
		return $this->add_relationship('and', true, $relationship, $condition);
	}
	
	public function or_related_not($relationship, $condition) {
		return $this->add_relationship('or', true, $relationship, $condition);
	}
	
	public function xor_related_not($relationship, $condition) {
		return $this->add_relationship('xor', true, $relationship, $condition);
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
	
	public function or_where_ot() {
		return $this->add_condition('or', true, func_get_args());
	}
	
	public function xor_where_not() {
		return $this->add_condition('xor', true, func_get_args());
	}
	
}