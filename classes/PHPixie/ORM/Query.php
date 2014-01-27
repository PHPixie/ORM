<?php

namespace PHPixie\ORM;

class Query{
	
	protected $driver;
	protected $query;
	protected $mapper;
	
	public function _get() {
	
	}
	
	public function __call($method, $args) {
		if ($this->driver->handle_condition_call($method, $args))
			return $this;
	}
	
	public function start_condition_group($logic = 'and', $builder_name = null) {
		$this->condition_builder($builder_name)->start_group($logic);
		return $this;
	}
	
	public function end_condition_group($builder_name, $logic = 'and') {
		$this->condition_builder($builder_name)->end_group($logic);
		return $this;
	}
	
	public function where() {
		return $this->add_condition(func_get_args(), 'and', false, 'where');
	}
	
	public function or_where() {
		return $this->add_condition(func_get_args(), 'or', false, 'where');
	}
	
	public function xor_where() {
		return $this->add_condition(func_get_args(), 'xor', false, 'where');
	}
	
	public function where_not() {
		return $this->add_condition(func_get_args(), 'and', true, 'where');
	}
	
	public function or_where_not() {
		return $this->add_condition(func_get_args(), 'or', true, 'where');
	}
	
	public function xor_where_not() {
		return $this->add_condition(func_get_args(), 'xor', true, 'where');
	}
	
	public function start_where_group($logic = 'and') {
		return $this->start_condition_group($logic, 'where');
	}
	
	public function end_where_group() {
		return $this->end_condition_group('where');
	}
	
	public function _and() {
		return $this->add_condition(func_get_args(), 'and', false);
	}
	
	public function _or() {
		return $this->add_condition(func_get_args(), 'or', false);
	}
	
	public function _xor() {
		return $this->add_condition(func_get_args(), 'xor', false);
	}
	
	public function _and_not() {
		return $this->add_condition(func_get_args(), 'and', true);
	}
	
	public function _or_not() {
		return $this->add_condition(func_get_args(), 'or', true);
	}
	
	public function _xor_not() {
		return $this->add_condition(func_get_args(), 'xor', true);
	}
	
	public function start_group($logic='and') {
		return $this->start_condition_group($logic);
	}
	
	public function end_group() {
		return $this->end_condition_group();
	}
}