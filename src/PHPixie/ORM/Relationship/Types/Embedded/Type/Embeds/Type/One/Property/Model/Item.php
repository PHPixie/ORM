<?php

namespace PHPixie\ORM\Relationship\Types\Embeds\Property\Model;

class EmbeddedModel extends PHPixie\ORM\Relationship\Types\Embeds\Property\Model
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
		return $this->handler->getEmbeddedModel($this->embedConfig, $this->model);
	}
	
	public function create()
	{
		$this->loaded = true;
		$this->value = $this->handler->createEmbeddedModel($this->embedConfig, $this->model);
        $this->handler->setOwnerProperty($this->embedConfig, $this->value, null);
	}
	
	public function remove()
	{
		$this->handler->removeEmbeddedModel($this->embedConfig, $this->model);
        $this->handler->setOwnerProperty($this->embedConfig, $this->value);
	}
	
	public function set($model)
	{
		$this->loaded = true;
		return $this->handler->setEmbeddedModel($this->embedConfig, $this->model, $model);
        $this->handler->setOwnerProperty($this->embedConfig, $model, $this->model);
	}
}