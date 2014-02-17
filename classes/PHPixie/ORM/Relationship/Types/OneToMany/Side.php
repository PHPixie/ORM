<?php

namespace PHPixie\ORM\Relationships\Types\OneToMany;

class Side extends PHPixie\ORM\Relationship\Side {
	
	public function model_name() {
		if($this->type === 'owner'){
			return $this->config->get($this->config->items_model);
		}else {
			return $this->config->get($this->config->owner_model);
		}
	}
	
	public function property_name() {
		return $this->config->get($this->type.'_property');
	}
}