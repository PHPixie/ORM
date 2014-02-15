<?php

namespace PHPixie;

class ORM {
	
	protected $relationship_map;
	protected $repository_registry;
	
	public function relationship_map() {
		if($this->relationship_map === null)
			$this->relationship_map = $this->build_relationship_map();
		return $this->relationship_map;
	}
	
	protected function build_relationship_map() {
		$relationship_config = $this->config->slice('relationships');
		return new \PHPixie\ORM\Relationship\Map($this, $relationship_config);
	}
	
	public function repository_registry() {
		if($this->repository_registry === null)
			$this->repository_registry = $this->build_repository_registry();
		return $this->repository_registry;
	}
	
	protected function build_repository_registry() {
		$model_config = $this->config->slice('models');
		return new \PHPixie\ORM\Relationship\Registry($this, $model_config);
	}
	
	public function operator($field, $operator, $values) {
		return new \PHPixie\ORM\Conditions\Condition\Operator($field, $operator, $values);
	}
	
	public function condition_group() {
		return new \PHPixie\ORM\Conditions\Condition\Group;
	}
	
	public function relationship_group($relationship) {
		return new \PHPixie\ORM\Conditions\Condition\Group\Relationship($relationship);
	}
}
