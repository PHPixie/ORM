<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

class Adapter {
	
	protected $multiquery_strategy;
	
	public function __construct($multiquery) {
		$this->multiquery_strategy = $multiquery_strategy;
	}
	
	public function add_model_condition($query, $model, $model_field, $model_field) {
		$query->get_builder('where')
								->add_operator_condition(
									$item_condition->logic(), $item_condition->negated(), 
									$config['item_key'], '=', $model->id()
								);
	}
	
	
	
	public abstract function set_collection();
	
	public function subquery_strategy($config) {
		return $this->multiquery_strategy;
	}
}