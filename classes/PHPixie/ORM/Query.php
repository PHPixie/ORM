<?php

namespace PHPixie\ORM;

class Query{
	
	protected $condition_builder;
	protected $mapper;
	protected $limit;
	protected $offset;
	protected $order_by = array();
	
	public function ->condition_builder($condition_builder, $mapper) {
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
	
	public function end_condition_group($logic = 'and') {
		$this->condition_builder->->end_group($logic);
		return $this;
	}
	
	public function add_condition($logic, $negate, $args) {
		$this->condition_builder->add_condition($logic, $negate, $args);
		return $this;
	}
	
	public function start_group($logic='and', $negate = false) {
		$this->condition_builder->start_group($logic, $negate);
		return $this;
	}
	
	public function end_group() {
		$this->condition_builder->end_group();
		return $this;
	}
	
	public function add_collection($logic, $negate, $collection_items) {
		$this->condition_builder->add_condition($logic, $negate, $collection_items);
		return $this;
	}
	
	public function add_related($logic, $negate, $collection_items) {
		$this->condition_builder->add_condition($logic, $negate, $collection_items);
		return $this;
	}
	
	public function add_related($logic, $negate, $relationship, $condition) {
		$this->condition_builder->add_related($logic, $negate, $relationship, $condition);
		return $this;
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