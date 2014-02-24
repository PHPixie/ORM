<?php

namespace PHPixie\ORM\Relationships\Types\OneToMany\Link;

class Config extends PHPixie\ORM\Relationship\Side\Config {
	
	public $owner_model;
	public $item_model;
	public $item_key;
	public $owner_property;
	public $item_property;
	
	protected function process_config($config, $inflector) {
		$this->owner_model = $config->get('owner.model');
		$this->item_model = $config->get('items.model');
		$this->item_key = $config->get('items.owner_key', $this->owner_model.'_id');
		
		if (($this->owner_property = $config->get('owner.items_property', null)) === null)
			$this->owner_property = $inflector->plural($items_model);
		
		$this->item_property = $config->get('owner.owner_property', $owner_model);
	}
}