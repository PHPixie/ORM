<?php 

namespace PHPixie\ORM\Mapper;

class Preload {
	
	public function preloaders($model_name, $relationships, $plan) {
		foreach($relationships as $relationship) {
			$path = explode('.', $relationship);
			foreach($path as $rel) {
				$handler->preloader($plan);
			}
		}
	}
	
	protected function reltionship_map($relationships) {
		$map = array();
		foreach($relationships as $relationship) {
			
		}
	}
	
	protected function preloader($owner_model, $property, $result, $plan) {
		$handler = $this->registry->property_handler($owner_model, $property);
		$handler->preload($owner_model, $property, $result, $plan);
	}
}