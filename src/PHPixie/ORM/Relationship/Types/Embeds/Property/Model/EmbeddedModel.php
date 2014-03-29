<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Property\Model;

class EmbeddedModel extends PHPixie\ORM\Relationship\Types\Embeds\Property\Model
{


	public function __invoke($createMissing = false)
	{
		if (!$this->loaded)
			$this->reload($createMissing);
		
		if ($createMissing && $this->value === null)
			$this->create();
		
		return $this->value;
	}
	
	protected function load()
	{
		return $this->handler->getEmbedded($this->model, 
	}
	
	public function create()
	{
	
	}
}