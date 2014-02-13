<?php 

namespace PHPixie\ORM\Mapper;

class Preload {
	
	public function preloaders($loader, $relationships, $plan) {
		foreach($relationships as $relationship) {
			$this->preload_path($loader, $relationship);
		}
	}
	
	protected function preload_path($loader, $relationship) {
		$current_loader = $loader;
		foreach(explode('.', $relationship) as $property) {
			$preloader = $current_loader->preloader($property);
			if ($preloader === null) {
				$preloader = $this->preloader();
				$current_loader->add_preloader($property, $preloader);
			}
			$current_model = $preloader;
		}
	}
	
	protected function preloader($owner_model, $property, $result, $plan) {
		$handler = $this->registry->property_handler($owner_model, $property);
		$handler->preload($owner_model, $property, $result, $plan);
	}
}