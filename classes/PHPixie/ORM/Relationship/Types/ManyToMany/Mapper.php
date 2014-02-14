<?php

namespace PHPixie\ORM\Relationships\ManyToMany;

class Mapper {
	
	protected function normalize_config($config) {
		$left_model = $config->get('left.model');
		$right_model = $config->get('right.model');
		
		$left_repo = $this->registry->get($left_model);
		$right_repo = $this->registry->get($right_model);
		
		
		$config = array(
			'left_model' => $left_model,
			'right_model' => $right_model,
			
			'left_model_connection' => $owner_model->connection_name(),
			'right_model_connection' => $item_model->connection_name(),
			
			'left_id_field' => $left_repo->id_field(),
			'right_id_field' => $right_repo->id_field(),
			
			'linker' => $config->get('linker', $left_plural.'_'.$right_plural),
		);
		
		foreach(array('left', 'right') as $side) {
			
			if ($property = $config->get("{$side}.property", null)) {
				
				$config["{$side}_property"] = $property;
				$default_key = $this->inflector->singular($property).'_id';
				
			}else {
				
				$opposing_side = $side === 'left' ? 'right' : 'left';
				$opposing_model = $config["{$opposing_side}_model"];
				
				$config["{$side}_property"] = $this->inflector->plural($opposing_model);
				$default_key = $opposing_model.'_id';
				
			}
			
			$config["linker_{$side}_key"] = $config->get("{$side}.linker_key", $default_key);
		}
		
		return $config;
	}
	
	protected function relationship_properties($config) {
		return array(
			$params['left_model'] => array(
				$params['left_property']
			),
			
			$params['right_model'] => array(
				$params['right_property']
			),
		);
	}
}