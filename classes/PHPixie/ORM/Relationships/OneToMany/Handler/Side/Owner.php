<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler\Side;

class Owner extends \PHPixie\ORM\Relationships\OneToMany\Handler\Side {

	protected $db;
	protected $mapper;
	
	public function __construct($db, $mapper) {
		$this->db = $db;
		$this->mapper = $mapper;
	}
	
	public function ids_query($config, $conditions) {
		$repo = $config['owner_repo'];
		$query = $repo->connection()->query('select')
										->fields(array($repo->id_field()));
		$this->set_collection($query, $repo);
		$this->mapper->add_conditions($query, $conditions);
		return $query;
	}
	

	public function add_owner_conditions($config, $query, $group, $plan) {
		$subquery = $this->ids_query($config, $group->conditions());
		$this->subquery_strategy($config)->add_condition($query, $group->logic, $group->negated(), $config['item_key'], $subquery);
	}
	
	public function add_owner_relationship_condition($config, $query, $plan, $condition) {
		return $this->add_relationship_condition('owner', $config, $query, $plan, $condition);
	}
	
}