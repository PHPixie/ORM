<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler\Owner;

class PDO extends \PHPixie\ORM\Relationships\OneToMany\Handler\Owner {
	
	protected function set_collection($query, $repository) {
		$query->table($repository->table());
	}
	
}