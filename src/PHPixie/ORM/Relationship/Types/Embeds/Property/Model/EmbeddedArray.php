<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Property\Model;

class EmbeddedArray extends PHPixie\ORM\Relationship\Types\Embeds\Property\Model
{
	public function __invoke($createMissing = false)
	{
		if (!$this->loaded)
			$this->reload();
		
		if ($createMissing && $this->value === null)
			$this->create();
		
		return $this->value;
	}
	
	protected function load()
	{
		return $this->handler->getEmbedded($this->model, $this->embedConfig);
	}
	
	public function create()
	{
		$this->loaded = true;
		return $this->handler->createEmbedded($this->model, $this->embedConfig);
	}
	
	public function remove()
	{
		return $this->handler->removeEmbedded($this->model, $this->embedConfig);
	}
	
	public function set($model)
	{
		$this->loaded = true;
		return $this->handler->setEmbedded($this->model, $this->embedConfig, $model);
	}
}