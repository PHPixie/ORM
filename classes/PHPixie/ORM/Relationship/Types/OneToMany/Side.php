<?php

namespace PHPixie\ORM\Relationships\Types\OneToMany;

class Side extends PHPixie\ORM\Relationship\Side {
	
	public function model_name() {
		return $this->config->get($this->type.'_model');
	}
	
	public function property_name() {
		return $this->config->get($this->type.'_property');
	}
}