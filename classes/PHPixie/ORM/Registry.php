<?php

namespace PHPixie\ORM;

class Registry {
	
	protected $property_map = array();
	protected $handlers = array();
	
	protected function map_properties() {
		$relationships = $this->config->get('relationships');
		
		foreach($relationships->data() as $key => $params) {
			$type = $params['type'];
			$handler = $this->handler($type);
			$model_properties = $handler->add_relationship($config->slice($key));
		}
		
		$this->update_handled_properties();
	}
	
	
	
	public function update_handled_properties() {
		$this->property_map = array();
		foreach($this->handlers as $type => $handler)
			foreach($handler->handled_properties() as $model => $properties)
				$this->add_model_properties($model, $properties, $type);
	}
	
	public function add_model_properties($model, $property_names, $type) {
		if (!isset($this->property_map[$model]))
			$this->property_map[$model] = array();
		
		foreach($properties as $property) {
			if (isset($this->property_map[$model][$property])) 
				throw new \PHPixie\ORM\Exception\Mapper("Property '$property' on '$model' model has already been defined by a different relationship.");
			$this->property_map[$model][$property] = $type;
		}
	}
	
	public function handler($type) {
		if (!isset($this->handlers[$type]))
			$this->handlers[$type] = $this->orm->handler($type);
		
		return $this->handlers[$type];
	}
	
	public function property($model, $property_name) {
		$type = $this->propety_map[$model->model_name()][$property_name];
		return $this->handler($type);
	}
}