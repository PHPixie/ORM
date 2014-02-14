<?php

namespace PHPixie\ORM\Relationship\ManyToMany;

class Property {
	
	protected $side;
	protected $
	
	public function __construct($side) {
		parent::__construct();
		$this->side = $side;
	}
	
	public function add($item) {
		$this->handler->add_item($side, $this->model, $this->name, $item);
	}
	
	public function query() {
		return $this->handler->relationship_side_query($side, $this->model, $this->name);
	}
	
	public function __invoke($cache = false) {
		
	}
}