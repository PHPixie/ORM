<?php

namespace PHPixie\ORM\Relationship\Type;

class Handler{
	
	protected $orm;
	protected $relationship;
	
	public function __construct($orm, $relationship) {
		$this->orm = $orm;
		$this->relationship = $relationship;
	}
	
	protected function build_related_query($model_name, $property, $related) {
		return $this->orm->query($model_name)
								->related($property, $related_model);
	}
	
}