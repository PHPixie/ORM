<?php

namespace PHPixie\ORM\Relationship;

abstract class Type {

	public function get_sides($config) {
		$config = $this->config($config);
		$sides = array();
		foreach($this->sides($config) as $side)
			$sides[] = $this->side($side, $config);
	}
	
	public abstract function config($config);
	public abstract function side($type, $config);
	protected abstract function sides($config);
	
}