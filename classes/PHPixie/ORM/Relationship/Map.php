<?php

namespace PHPixie\ORM\Relationship;

class Map {
	
	protected $property_map = array();
	
	public function __construct($orm, $config) {
		foreach($config->data() as $key => $params) {
			$type = $params['type'];
			$relationship_config = $this->config->slice($key);
			$relationship = $orm->relationship($type);
			$links = $relationship->get_links($relationship_config);
			foreach($links as $link)
				$this->add_link($link);
		}
	}
	
	public function add_link($link) {
		$model_name = $link->model_name();
		$property_name = $link->property_name();
		
		if (!isset($this->property_map[$model_name]))
			$this->property_map[$model_name] = array();
		
		if (isset($this->property_map[$model_name][$property_name])) 
			throw new \PHPixie\ORM\Exception\Mapper("Property '$property_name' on '$model_name' model has already been defined by a different relationship.");
		
		$this->property_map[$model][$property] = $link;
	}
	
	public function get_link($model_name, $property_name) {
		return $this->property_map[$model_name][$property_name];
	}
	
}