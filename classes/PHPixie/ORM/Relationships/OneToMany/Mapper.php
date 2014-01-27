<?php

namespace PHPixie\ORM\Relationships\OneToMany;

class Mapper {
	
	protected function normalize_config($config) {
		$owner_model = $config->get('owner.model');
		$items_model = $config->get('items.model');
		
		$owner_repo = $this->registry->get($owner_model);
		$items_repo = $this->registry->get($items_model);
		
		return array(
			'owner_model' => $owner_model,
			'items_model' => $items_model,
			
			'owner_model_connection' => $owner_model->connection_name(),
			'item_model_connection' => $item_model->connection_name(),
			
			'item_key' => $config->get('items.owner_id', $owner_model.'_id'),
			'owner_id_field' => $owner_repo->id_field(),
			
			'owner_items_property' => $this->inflector->plural($items_model),
			'items_model_property' => $owner_model
		);
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
}