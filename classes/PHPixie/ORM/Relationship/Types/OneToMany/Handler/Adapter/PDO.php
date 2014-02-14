<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler\Adapter;

class PDO extends \PHPixie\ORM\Relationships\OneToMany\Handler\Adapter{
	public function set_collection($query, $repository) {
		$query->table($repository->table());
	}
	
	public function subquery_strategy($config) {
		if ($config['owner_repo']->connection_name() === $config['item_repo']->connection_name())
			return $this->inquery_strategy;
		
		return $this->multiquery_strategy;
	}
}