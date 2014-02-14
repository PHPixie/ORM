<?php 

namespace PHPixie\ORM\Mapper;

class Preload {
	
	public function preloaders($loader, $relationships, $plan) {
		foreach($relationships as $relationship) {
			$this->preload_path($loader, $relationship, $plan);
		}
	}
	
	protected function preload_path($loader, $relationship, $plan) {
		$current_loader = $loader;
		foreach(explode('.', $relationship) as $property) {
			$preloader = $current_loader->preloader($property);
			if ($preloader === null) {
				$preloader = $this->preloader($current_loader, $property, $plan);
				$current_loader->add_preloader($property, $preloader);
			}
			$current_model = $preloader;
		}
	}
	
	protected function preloader($loader, $property, $plan) {
		$handler = $this->registry->property_handler($loader->model_name(), $property);
		$handler->preload($loader, $property, $plan);
	}
}