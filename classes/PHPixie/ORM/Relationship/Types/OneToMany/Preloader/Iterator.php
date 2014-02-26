<?php

namespace PHPixie\ORM\Relationships\OneToMany\Preloader;

class Iterator extends \PHPixie\ORM\Iterator\Data {
	
	protected $preloader;
	
	protected function __construct($preloader, $ids) {
		$this->preloader = $preloader;
		parent::__construct($ids);
	}
	
	public function current() {
		$id = parent::current();
		$this->preloader->get($id);
	}
}