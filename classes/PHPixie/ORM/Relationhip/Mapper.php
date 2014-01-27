<?php

namespace PHPixie\ORM\Relationship;

class Mapper {
	
	protected $property_map = array();
	
	public function map_properties($relationship_config) {
		$config = $this->normalize_config($relationship_config);
		$model_properties = $this->handled_properties($config);
		
		foreach($model_properties as $model => $properties) {
			if (!isset($this->property_map[$model]))
				$this->property_map[$model] = array();
			
			foreach($properties as $property => $type) {
				if (isset($this->property_map[$model][$property]))
					throw new \PHPixie\ORM\Exception("This model hasn't been saved to the database, so it can't be deleted");
					
				$this->property_map[$model][$property] = array(
					'config' => &$config,
					'type'   => $type
				);
				
			}
		}
	}
	
	public function property($model_name, $property_name) {
		$params = $this->property_map[$model_name][$property_name];
		return $this->build_property($params['type']);
	}
	
	public function config($model_name, $property_name) {
		$params = $this->property_map[$model_name][$property_name];
		return $params['config'];
	}
	
	abstract protected function normalize_config($relationship_config);
	abstract protected function relationship_properties($config);
	
}