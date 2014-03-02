<?php

namespace PHPixie\ORM\Model;

class Loader{
	protected $repository;
	protected $preloaders = array();
	
	public function __construct($repository) {
		$this->repository = $loader;
	}
	
	public function get_preloader($property) {
		if (isset($this->preloaders[$property]))
			return $this->preloaders[$property];
		return null;
	}
	
	public function add_preloader($property, $preloader) {
		$this->preloaders[$property] = $preloader;
	}
	
	public function load($data) {
		$model = $this->repository->load($data);
		foreach($preloaders as $property => $preloader)
			$model->$property->set_value($preloader->load_for($model));
	}
}