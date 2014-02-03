<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

class Pivot {
	
	public function ids_query($conditions, $side, $opposing_side, $opposing_handler, $config, $plan) {
		
		$subquery = $opposing_handler->ids_query($conditions, $config, $plan);
		
		$query = $repo->connection()->query('select')
										->fields(array($config["pivot_{$side}_key"]));
		
		$this->set_collection($query, $config["pivot"]);
		$this
			->pivot_strategy($config)
			->add_condition($query, 'and', false, $config["pivot_{$opposing_side}_key"], $subquery);
			
		return $query;
	}
	
	protected function set_collection($query, $pivot) {
		$this->adapter->set_collection($query, $pivot);
	}
	
	protected function pivot_strategy($side, $config) {
		return $this->adapter->pivot_strategy($side, $config);
	}
	
}