<?php

namespace PHPixie\ORM\Relationships\OneToMany;

class Preloader implements \PHPixie\ORM\Relationships\Type\Preloader{
	
	protected $reusable_result_step;
	protected $repository;
	
	public function __construct($reusable_result_step, $repository) {
		$this->reusable_result_step = $reusable_result_step;
		$this->repository = $repository;
	}
}