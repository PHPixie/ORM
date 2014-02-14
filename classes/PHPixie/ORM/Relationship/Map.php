<?php

namespace PHPixie\ORM\Relationship;

class Map {
	
	protected $orm;
	protected $config;
	
	protected $property_map = array();
	
	public function __construct($orm, $config) {
		$this->orm = $orm;
		$this->config = $config;
		$this->map_properties();
	}
	
	protected function map_properties() {
		foreach($this->config->data() as $key => $params) {
			$type = $params['type'];
			$relationship = $this->relationship($type);
			$sides = $relationship->get_sides($config);
			foreach($sides as $side)
				$this->add_side($side);
		}
	}
	
	public function add($side) {
		$model_name = $side->model_name();
		$property_name = $side->property_name();
		
		if (!isset($this->property_map[$model_name]))
			$this->property_map[$model_name] = array();
		
		if (isset($this->property_map[$model_name][$property_name])) 
			throw new \PHPixie\ORM\Exception\Mapper("Property '$property_name' on '$model_name' model has already been defined by a different relationship.");
		
		$this->property_map[$model][$property] = $side;
	}
	
	public function get($model_name, $property_name) {
		return $this->property_map[$model_name][$property_name];
	}
	
}