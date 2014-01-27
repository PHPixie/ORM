<?php

namespace PHPixie\ORM\Relationships;

class ManyToMany extends PHPixie\ORM\Relationship {
	
	protected function _property() {
		return new \PHPixe\ORM\Relationship\OneToMany\Property\Items();
	}
	
	protected function build_property($type) {
		if ($type === 'items')
			return $this->owner_items_property();
		
		if ($type === 'owner')
			return $this->item_owner_property();
		
		throw new \PHPixie\ORM\Exception\Relationship("Property type '$type' is not spported by one_to_many relationship.");
	}
	
	protected function relationship_properties($config) {
		return array(
			$params['owner_model'] => array(
				$params['owner_items_property']
			),
			
			$params['item_model'] => array(
				$params['item_owner_property']
			),
		);
	}
	
	public function normalize_config($config) {
		$owner_model = $config->get('owner.model');
		$items_model = $config->get('items.model');
		
		$owner_repo = $this->registry->get($owner_model);
		$items_repo = $this->registry->get($items_model);
		
		return array(
			'owner_model' => $owner_model,
			'items_model' => $items_model,
			
			'item_key' => $config->get('items.owner_id', $owner_model.'_id'),
			'owner_id_field' => $owner_repo->id_field(),
			
			'owner_items_property' => $this->inflector->plural($items_model),
			'items_model_property' => $owner_model
		);
	}
}