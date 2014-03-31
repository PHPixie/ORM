<?php

namespace PHPixie\ORM\Relationship\Types\OneTo\Property;

abstract class Model extends \PHPixie\ORM\Relationship\Type\Property\Model
{
    protected $handler;
	
	public function load()
	{
		return $this->query()->find();
	}
	
	protected function query()
	{
		return $this->handler->query($this->side, $this->model);
	}
}
