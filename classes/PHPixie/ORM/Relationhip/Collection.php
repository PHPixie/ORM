<?php

namespace PHPixie\ORM\Relationship;

abstract class Collection {
	
	protected $models = array();
	protected $queries = array();
	
	protected $required_model;
	
	public function __construct($required_model) {
		$this->required_model = $model;
	}
	
	public function add($items) {
		if (!is_array($items))
			$items = array($items);
		
		foreach($items as $item)
			$this->add_item($item, $required_model, $update_list);
		
		return $update_list;
	}
	
	protected function add_query($query) {
		$this->queries[] = $query;
	}
	
	protected function add_model($model) {
		$this->queries[] = $model;
	}
	
	protected function add_item($item) {
		if($item instanceof \PHPixie\ORM\Model) {
			if($item->model_name() !== $this->required_model)
				throw new \PHPixie\ORM\Exception\Mapper("Only items of the '$required_model' models are supported by this relationship.");
				
			$this->add_model($item);
			
		}elseif($item instanceof \PHPixie\ORM\Query) {
			if ($item->model_name() !== $model_name)
				throw new \PHPixie\ORM\Exception\Mapper("You can add only $model_name items to this relationship.");
			
			$this->add_query($item);
			
		}else
			throw new \PHPixie\ORM\Exception\Mapper("You can add only $model_name items to this relationship.");
	}
}