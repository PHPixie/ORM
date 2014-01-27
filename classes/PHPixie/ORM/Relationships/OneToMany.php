<?php

namespace PHPixie\ORM\Relationships;

class OneToMany extends PHPixie\ORM\Relationship {
	
	protected function build_mapper() {
		return new \PHPixie\ORM\Relationships\OneToMany\Mapper();
	}
	
	protected function build_property($type) {
		$class = '\PHPixe\ORM\Relationship\OneToMany\Property\\'.ucfirst($type);
		return new $class();
	}
}