<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Property\Model;

class Owner extends PHPixie\ORM\Relationship\Types\Embeds\Property\Model
{
	public function __invoke($createMissing = false)
	{
		return $this->value;
	}
	
	protected function load()
	{
	}

	public function set($owner)
	{
		
		$this->handler->setEmbedded($this->embedConfig, $owner, $this->model);
		
	}
}