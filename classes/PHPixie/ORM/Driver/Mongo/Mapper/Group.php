<?php

namespace PHPixie\ORM\Driver\Mongo\Mapper;

class Group {
	public function requires_subquery($model, $relationship) {
		$path = explode('.', $relationship);
		$current = $model_name;
		foreach($path as $relation) {
			$config = $this->configs[$current][$relation];
			if ($config['method'] === 'reference')
				return true;
			$current = $config['model_name'];
		}
		
		return false;
	}
	
	public function prepare_queries($conditions, $runner) {
		foreach($conditions as $key => $condition) {
			if (!is_numeric($key)) {
				$subquery = $this->build_subquery($model_name, $key)
			}
			
			
		}
	}
	
	public function 
	
}