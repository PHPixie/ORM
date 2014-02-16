<?php

namespace PHPixie\ORM\Relationship;

abstract class Type {

	public function get_sides($config) {
		$config = $this->config($config);
		$sides = array();
		foreach($this->side_types($config) as $side)
			$sides[] = $this->side($side, $config);
	}
	
	public abstract function config($config);
	public abstract function side($type, $config);
	public abstract function model_property($side, $model);
	public abstract function query_property($side, $model);
	
	protected abstract function side_types($config);
	
}