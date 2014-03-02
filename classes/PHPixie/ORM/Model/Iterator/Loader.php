<?php

namespace PHPixie\ORM\Model\Iterator;

class Loader extends \PHPixie\ORM\Model\Iterator implements \Countable{

	protected $loader;
	protected $data_iterator;
	protected $current_model;
	
	public function __construct($loader, $data_iterator) {
		$this->loader = $loader;
		$this->data_iterator = $data_iterator;
	}

	
	public function current() {
		if ($this->current_model === null)
			$this->current_model = $this->loader->load($this->data_iterator->current());
		return $this->current_model;
	}
	
	public functiom
	
}