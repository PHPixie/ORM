<?php

namespace \PHPixie\ORM\Relationships\OneToMany\Handler;

abstract class Adapter {
	
	abstract protected function set_repository($query, $repository);
	
	abstract protected function pivot_strategy($side, $config);
	
	abstract protected function set_collection($query, $collection);
}