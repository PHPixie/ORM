<?php

namespace PHPixie\ORM\Relationships\OneToMany;

class Mapper {
	
	protected function normalize_config($config) {
		$normalized = array();
		
		$sides = array(
			'left' => array('opposing' => 'right'),
			'right' => array('opposing' => 'left'),
		);
		
		foreach($sides as $side => $params) {
			$opposing = $params['opposing'];
			
			$model = $config->get($opposing);
			$normalized["{$side}_model"] = $model;
			
			if (($plural = $config->get("{$opposing}_property", null)) === null)
				$plural = $this->repository_registry($model)->plural_name();
			$sides[$side]['plural'] = $plural;
			$normalized["{$opposing}_property"] = $plural;
			
			if (($pivot_key = $config->get("{$side}_pivot_key", null)) === null)
				$pivot_key = $this->inflector->singular($plural).'_id';
			$normalized["{$side}_pivot_key"] = $pivot_key;
		}
		
		if (($pivot = $config->get('pivot', null)) === null)
			$pivot = $sides['left']['plural'].'_'.$sides['right']['plural'];
		$normalized['pivot'] = $pivot;
		
		if (($pivot_connection = $config->get('pivot_connection', null)) === null)
			$pivot_connection = $this->repository_registry($normalized["left_model"])->connection_name();
		$normalized['pivot_connection'] = $pivot_connection;
		
		return $normalized;
	}
}