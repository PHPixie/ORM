<?php

namespace PHPixie\ORM\Conditions;

class Builder extends \PHPixie\DB\Conditions\Builder {
	
	protected $orm;
	
	public function __construct($orm, $default_operator = '=') {
		$this->orm = $orm;
		$this->default_operator = $default_operator;
		$this->current_group = $orm->condition_group();
		$this->group_stack[] = $this->current_group;
		
	}
	
	public function operator($field, $operator, $values) {
		return $this->orm->operator($field, $operator, $values);
	}
	
	public function condition_group() {
		return $this->orm->condition_group();
	}
	
	public function add_operator_condition($logic, $negate, $field, $operator, $values) {
		$relationship = null;
		if(($pos = strrpos($field, '.')) !== false) {
			$relationship = substr($field, 0, $pos);
			$field = substr($field, $pos+1);
		}
		$condition = $this->build_operator_condition($negate, $field, $operator, $values);
		
		if($relationship === null) {
			parent::add_operator_condition($logic, $negate, $field, $operator, $values);
		}else {
			$this->start_relationship_group($logic, $relationship);
			$this->current_group->add($condition);
			$this->end_group();
		}
	}
	
	public function relationship_group($relationship) {
		return $this->orm->relationship_group($relationship);
	}
	
	public function start_relationship_group($logic, $relationship) {
		$current = $this->current_group;
		
		foreach(explode('.', $relationship) as $key => $rel) {
			$group = $this->relationship_group($rel);
			$this->add_group_to_group($key === 0 ? $logic:'and', $group, $current);
			$current = $group;
		}
		
		$this->group_stack[]=$group;
		$this->current_group = $group;
		return $this;
	}
	
	public function add_relationship_group($logic, $negate, $args) {
		echo(6); die;
		if ($negate)
			$logic = $logic.'_not';
			
		$count = count($args);
		
		if($count === 2) {
			$relationship = $args[0];
			$callback = $args[1];
		}else
			throw new \PHPixie\ORM\Exception\Builder("Unexpected number of arguments passed");
		
		$this->start_relationship_group($logic, $relationship);
		call_user_func($callback, $this);
		$this->end_group();
		return $this;
	}
	
	public function add_relationship_condition($logic, $negate, $args) {
		$count = count($args);
		if ($count === 1)
			if (is_callable($callback = $args[0])) {
				if ($negate)
					$logic = $logic.'_not';
				$this->start_relationship_group($logic);
				$callback($this);
				$this->end_group();
				return $this;
			}else 
				throw new \PHPixie\DB\Exception("If only one argument is provided it must be a callable");
		
		throw new \PHPixie\DB\Exception("Not enough arguments provided");
	}
	
	public function related() {
		return $this->add_relationship_group('and', false, func_get_args());
	}
	
	public function or_related() {
		return $this->add_relationship_group('or', false, func_get_args());
	}
	
	public function xor_related() {
		return $this->add_relationship_group('xor', false, func_get_args());
	}
	
	public function and_related_not() {
		return $this->add_relationship_group('and', true, func_get_args());
	}
	
	public function or_related_not() {
		return $this->add_relationship_group('or', true, func_get_args());
	}
	
	public function xor_related_not() {
		return $this->add_relationship_group('xor', true, func_get_args());
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