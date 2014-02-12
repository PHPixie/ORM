<?php

namespace PHPixie\ORM;

class Result impelements \Iterator{
	
	protected $repository;
	protected $data_iterator;
	protected $preloaders;

	protected $current;
	
	public function __construct($repository, $data_iterator, $preloaders) {
		$this->repository = $repository;
		$this->data_iterator = $data_iterator;
		$this->preloaders = $preloaders;
	}
	
	public function current() {
		if ($this->current_model === null)
			$this->current_model = $this->load_model($this->data_iterator->current());
		
		return $this->current_model;
	}

	public function key() {
		return $this->data_iterator->key();
	}

	public function valid() {
		return $this->data_iterator->valid();
	}
	
	public function next() {
		$this->data_iterator->next();
		$this->current_model = null;
	}
	
	public function rewind() {
		$this->data_iterator->rewind();
		$this->current_model = null;
	}
	
	protected function load_model($data) {
		$model = $this->repository->load_model($data);
		foreach($this->preloaders as $preloader) 
			$preloader->preload_for($model);
	}
}