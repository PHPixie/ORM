<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

abstract class Side {
	
	protected $side;
	protected $adapter;
	
	public function __construct($adapter) {
		$this->adapter = $adapter;
	}
	
	public function ids_query($conditions, $config, $plan) {
		$repo = $config["{$this->side}_repo"];
		
		$query = $repo->connection()->query('select')
										->fields(array($repo->id_field()));
		
		$this->set_repository($query, $repo);
		$this->mapper->add_conditions($query, $conditions, $repo->model_name(), $plan);
		return $query;
	}
	
	protected function add_opposing_side_conditions($group, $opposing_side, $opposing_handler, $pivot_handler, $config, $query, $plan) {
		$pivot_subquery = $pivot_handler->ids_query($group->conditions(), $this->side, $opposing_side, $opposing_handler, $config, $plan);
		$this->pivot_strategy($config)->add_condition($query, $group->logic, $group->negated(), $config["{$this->side}_repo"]->id_field(), $pivot_subquery);
	}
	
	protected function add_opposing_side_items() {
		$keys = array(
					$config["{$side}_pivot_key"], 
					$config["{$opposing_side}_pivot_key"]
				);
		
		$values = array();
		foreach($ids as $id)
			$values[] = array($side_id, $id);
		
		$this->db->query('insert', $config['pivot_connection']);
															->target($config['pivot'])
															->batch_data($keys, $values)
															->execute();
	}
	
	protected function set_repository($query, $repository) {
		return $this->adapter->set_repository($query, $repository);
	}
	
	protected function pivot_strategy($config) {
		return $this->adapter->pivot_strategy($this->side, $config);
	}
}