<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

abstract class Item {

	protected $db;
	protected $adapter;
	protected $mapper;
	
	public function __construct($db, $mapper) {
		$this->db = $db;
		$this->mapper = $mapper;
	}
	
	public function keys_query($config, $conditions) {
		$repo = $config['item_repo'];
		$query = $repo->connection()->query('select')
										->distinct()
										->fields($config['item_key']);
		$this->adapter->set_collection($query, $repo);
		$this->mapper->add_conditions($query, $conditions);
		return $query;
	}
	
	public function add_item_conditions($owner_handler, $config, $query, $group, $relationship, $plan) {
		$subquery = $this->keys_query($config, $group->conditions());
		$this->subquery_strategy($config)->add_condition($query, $group->logic, $group->negated(), $config['owner_repo']->id_field, $subquery);
	}
	
	public function add_item_relationship_condition($config, $query, $plan, $condition) {
		return $this->add_relationship_condition('item', $config, $query, $plan, $condition);
	}
}