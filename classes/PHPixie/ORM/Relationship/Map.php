<?php

namespace PHPixie\ORM\Relationship;

class Map {
	
	protected $property_map = array();
	
	public function __construct($orm, $config) {
		foreach($config->data() as $key => $params) {
			$type = $params['type'];
			$relationship_config = $this->config->slice($key);
			$relationship = $orm->relationship($type);
			$sides = $relationship->get_sides($relationship_config);
			foreach($sides as $side)
				$this->add_side($side);
		}
	}
	
	public function add_side($side) {
		$model_name = $side->model_name();
		$property_name = $side->property_name();
		
		if (!isset($this->property_map[$model_name]))
			$this->property_map[$model_name] = array();
		
		if (isset($this->property_map[$model_name][$property_name])) 
			throw new \PHPixie\ORM\Exception\Mapper("Property '$property_name' on '$model_name' model has already been defined by a different relationship.");
		
		$this->property_map[$model][$property] = $side;
	}
	
	public function get_side($model_name, $property_name) {
		return $this->property_map[$model_name][$property_name];
	}
	
}