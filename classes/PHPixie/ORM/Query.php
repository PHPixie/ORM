<?php

namespace PHPixie\ORM;

class Query{
	
	protected $condition_builder;
	protected $mapper;
	protected $limit;
	protected $offset;
	protected $order_by = array();
	
	public function __construct($condition_builder, $mapper) {
		$this->condition_builder = $condition_builder;
		$this->mapper = $mapper;
	}
	
	public function limit($limit) {
		if (!is_numeric($limit))
			throw new \PHPixie\DB\Exception\Builder("Limit must be a number");
			
		$this->limit = $limit;
		return $this;
	}
	
	public function get_limit() {
		return $this->limit;
	}
	
	public function offset($offset) {
		if (!is_numeric($offset))
			throw new \PHPixie\DB\Exception\Builder("Offset must be a number");
			
		$this->offset = $offset;
		return $this;
	}
	
	public function get_offset() {
		return $this->offset;
	}
	
	public function order_by($field, $dir = 'asc') {
		if ($dir !== 'asc' && $dir !== 'desc')
			throw new \PHPixie\DB\Exception\Builder("Order direction must be either 'asc' or  'desc'");
		
		$this->order_by[] = array($field, $dir);
		return $this;
	}
	
	public function get_order_by() {
		return $this->order_by;
	}
	
	public function plan_delete($type) {
		return $this->mapper->map_delete($this);
	}
	
	public function plan_update($type, $data) {
		return $this->mapper->map_update($this, $data);
	}
	
	public function plan_find($type, $preload = array()) {
		return $this->mapper->map_find($this, $preload);
	}
	
	public function delete() {
		$this->delete_plan()->execute();
	}
	
	public function update($data) {
		$this->update_plan($data)->execute();
	}
	
	public function find($preload = array()) {
		return $this->plan_find($this, $preload);
	}
	
	public function find_one($preload = array()) {
		$old_limit = $this->get_limit();
		$this->limit(1);
		$iterator = $this->find_plan($preload)->execute();
		$this->limit($old_limit);
		return $iterator->current();
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