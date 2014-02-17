<?php

namespace PHPixie\ORM

class Collection {
	
	protected $models;
	protected $queries;
	protected $required_model;
	
	public function __construct($required_model) {
		$this->required_model = $required_model;
	}
	
	public function add($items) {
		if (!is_array($items)) {
			$items = array($items);
		}
		
		foreach($items as $item)
			$this->add_item($item);
	}
	
	protected function add_item($item, $recurse_array =  true) {
		
		if ($item instanceof \PHPixie\ORM\Model) {
			if ($item->model_name() !== $this->required_model)
				throw new \PHPixie\ORM\Exception\Mapper("Instance of the '{$item->model_name()}' model passed, but '{$this->required_model}' was expected.");
				
			if (!$item->loaded())
				throw new \PHPixie\ORM\Exception\Mapper("You can only use saved models.");
				
			$this->models[] = $item;
		}elseif($item instanceof \PHPixie\ORM\Query) {
			
			if ($item->model_name() !== $this->required_model)
				throw new \PHPixie\ORM\Exception\Mapper("Query for the '{$item->model_name()}' model passed, but '{$this->required_model}' was expected.");
				
			$this->queries[] = $item;
		}else
			throw new \PHPixie\ORM\Exception\Mapper("Only '{$this->required_model}' models and queries are allowed.");
	}
	
	public function added_models() {
		return $this->models;
	}
	
	public function added_queries() {
		return $this->queries;
	}
	
	public function fields($fields, $skip_queries = false) {
		$data = array();
		
		foreach($this->models as $model) {
			$data_row = array();
			
			foreach($fields as $field)
				$data_row[$field] = $model->$field;
				
			$data[] = $data_row;
		}
		
		if(!$skip_queries) {
			foreach($this->queries as $query) {
				$rows = $query->find_all()->as_data_array();
				foreach($rows as $row) {
					$data_row = array();
					foreach($fields as $field)
						$data_row[$field] = $row->$field;
					$data[] = $data_row;
				}
				
			}
		}
		
		return $data;
	}
	
	public function field($field, $skip_queries = false) {
		return array_column($this->fields(array($field), $skip_queries), $field));
	}
	
	
}